<?php

namespace App\Services;

use App\Models\BarOrderOutletStoreItem;
use App\Models\MenuItem;
use App\Models\MenuItemOrder;
use App\Models\OutletStoreItem;
use App\Models\PurchaseStoreItem;
use App\Models\RestaurantItem;
use App\Models\RestaurantOrderItem;
use App\Models\StoreIssueStoreItem;
use App\Models\StoreItem;
use App\Models\StoreItemActivity;
use Carbon\Carbon;

class StoreItemService
{
    public function getRevenueAndQuantitySoldMetrics($storeItemId, $startDate, $endDate)
    {
        // if item is food and is for sale use the menu_order_items table
        // if item is bar and is for sale use the outlet_store_items table
        //else return 0
        $metrics = [
            'revenue' => 0,
            'qty' => 0,
        ];;
        $storeItem = StoreItem::find($storeItemId);
        if (!$storeItem) {
            return $metrics;
        }
        if ($storeItem->item_category_id == 1 && $storeItem->for_sale) {
            $metrics = $this->getRestaurantRevenueAndQuantitySold($storeItem, $startDate, $endDate);
        } elseif ($storeItem->item_category_id == 2 && $storeItem->for_sale) {
            $metrics = $this->getBarRevenueAndQuantitySold($storeItem, $startDate, $endDate);
        }

        return $metrics;
    }

    public function getRestaurantRevenueAndQuantitySold($storeItem, $startDate, $endDate)
    {
        $menuItems = collect();

        foreach ($storeItem->outletStoreItems as $outletStoreItem) {
            $menuItems = $menuItems->merge($outletStoreItem->menuItems);
        }
        //$menuItems = $menuItems->pluck('id')->unique();
        //dd($menuItems);

        if ($menuItems->isEmpty()) {
            return [
                'revenue' => 0,
                'qty' => 0,
            ];
        }

        $query = MenuItemOrder::whereIn('menu_item_id', $menuItems)
            ->whereBetween('created_at', [$startDate, $endDate]);
        $revenue = $query->sum('amount');
        $qty = $query->sum('qty');
        return [
            'revenue' => $revenue,
            'qty' => $qty,
        ];
    }

    public function getPurchase($storeItemId, $startDate, $endDate)
    {
        // Get all purchases where the purchaseDate is between the start and end date
        //then get the sum of the amount where the store_item_id is in purchase_store_items
        return PurchaseStoreItem::join('purchases', 'purchase_store_items.purchase_id', '=', 'purchases.id')
            ->where('purchase_store_items.store_item_id', $storeItemId)
            ->whereBetween('purchases.purchase_date', [$startDate, $endDate])
            ->sum('purchase_store_items.total_amount');
    }

    public function getInventoryActivity($storeItem, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $dailyStats = [];

        // get the current qty of the last store_item_activity before the start date
        // this will be the initial balance before the start date
        $initialBalance = StoreItemActivity::where('store_item_id', $storeItem->id)
            ->whereDate('activity_date', '<', $startDate)
            ->orderBy('activity_date', 'desc') // Get the latest *before* the start date
            ->first()
            ->current_qty ?? 0;

        //if the initial balance from store activity is 0, meaning no activity was done. 
        //get the balance from the last purchase or from last import
        if ($initialBalance == 0) {
            $latestPurchaseRecord = PurchaseStoreItem::join('purchases', 'purchase_store_items.purchase_id', '=', 'purchases.id')
                ->where('purchase_store_items.store_item_id', $storeItem->id)
                ->whereDate('purchases.purchase_date', '<', $startDate)
                ->orderBy('purchases.purchase_date', 'desc')
                ->select('purchase_store_items.qty')
                ->first();

            $initialBalance = $latestPurchaseRecord?->qty ?? 0; // fallback to 0 if no record
        }

        // Calculate the running balance before the start date
        //$runningBalance = ($initialBalance + $initialIncoming) - $initialOutgoing - $initialSales;
        $runningBalance = $initialBalance;
        //dd($initialBalance, $initialIncoming, $initialOutgoing, $initialSales, $runningBalance);
        while ($start->lte($end)) {
            $date = $start->toDateString();

            // Incoming from purchases
            $incoming = PurchaseStoreItem::join('purchases', 'purchase_store_items.purchase_id', '=', 'purchases.id')
                ->where('purchase_store_items.store_item_id', $storeItem->id)
                ->whereDate('purchases.purchase_date', $date)
                ->sum('qty');

            // Outgoing to sales points
            $outgoing = StoreIssueStoreItem::join('store_issues', 'store_issue_store_items.store_issue_id', '=', 'store_issues.id')
                ->where('store_issue_store_items.store_item_id', $storeItem->id)
                ->whereDate('store_issues.created_at', $date)
                ->sum('qty');

            // Sales
            $sales = $this->getStoreItemSalesQty($storeItem, $date);

            // Calculate balance in the store
            $balance = $runningBalance + $incoming - $outgoing;
            $runningBalance = $balance;

            $dailyStats[] = [
                'date' => $date,
                'incoming' => $incoming,
                'outgoing' => $outgoing,
                'sales' => $sales,
                'balance' => $balance
            ];

            $start->addDay();
        }

        return $dailyStats;
    }

    public function getStoreItemSalesQty($storeItem, $date)
    {
        if ($storeItem->for_sale) {
            return MenuItemOrder::join('menu_items', 'menu_items.id', '=', 'menu_item_orders.menu_item_id')
                ->join('menu_item_outlet_store_items', 'menu_item_outlet_store_items.menu_item_id', '=', 'menu_items.id')
                ->join('outlet_store_items', 'outlet_store_items.id', '=', 'menu_item_outlet_store_items.outlet_store_item_id')
                ->join('orders', 'orders.id', '=', 'menu_item_orders.order_id')
                ->where('outlet_store_items.store_item_id', $storeItem->id)
                ->whereDate('orders.order_date', $date)
                ->sum('menu_item_orders.qty');
        }
        return 0;
    }
}
