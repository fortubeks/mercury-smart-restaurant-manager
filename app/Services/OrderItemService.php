<?php

namespace App\Services;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\MenuItemOrder;

class OrderItemService
{
    public function saveItemsAndUpdateStock(Order $order, array $items)
    {
        foreach ($items as $itemId => $item) {
            // Save order item
            $orderItem = MenuItemOrder::create([
                'order_id'       => $order->id,
                'menu_item_id'   => $itemId,
                'qty'            => $item['quantity'],
                'sub_total'      => $item['total'],
                'tax_rate'       => calculateTaxRate(),
                'tax_amount'     => $item['tax'],
                'discount_rate'  => 0,
                'discount_amount' => 0,
                'total_amount'   => $item['total'],
            ]);

            // Skip stock management if disabled
            if (!restaurant()->appSetting->manage_stock) {
                continue;
            }

            // Load menu item with all relations
            $menuItem = MenuItem::with(['components.ingredients.outletStoreItem', 'ingredients.outletStoreItem'])
                ->find($itemId);

            if (!$menuItem) {
                continue;
            }

            // Handle combo menu items
            if ($menuItem->is_combo) {
                foreach ($menuItem->components as $component) {
                    foreach ($component->ingredients as $ingredient) {
                        $outletStoreItem = $ingredient->outletStoreItem;

                        if ($outletStoreItem) {
                            $deductQty = $item['quantity'] * $ingredient->pivot->quantity_needed;
                            $outletStoreItem->qty -= $deductQty;
                            $outletStoreItem->save();
                        }
                    }
                }
            }
            // Handle regular menu items
            else {
                if ($menuItem->ingredients->isNotEmpty()) {
                    foreach ($menuItem->ingredients as $ingredient) {
                        $outletStoreItem = $ingredient->outletStoreItem;

                        if ($outletStoreItem) {
                            $deductQty = $item['quantity'] * $ingredient->pivot->quantity_needed;
                            $outletStoreItem->qty -= $deductQty;
                            $outletStoreItem->save();
                        }
                    }
                }
            }
        }
    }

    public function restoreItemsAndStock(Order $order)
    {
        if (!restaurant()->appSetting->manage_stock) {
            return;
        }

        // Load everything needed in one go
        $order->load([
            'menuItems.components.ingredients.outletStoreItem',
            'menuItems.ingredients.outletStoreItem'
        ]);

        foreach ($order->menuItems as $menuItem) {
            $orderedQty = $menuItem->pivot->qty;

            // Combo menu items
            if ($menuItem->is_combo) {
                foreach ($menuItem->components as $component) {
                    foreach ($component->ingredients as $ingredient) {
                        $outletStoreItem = $ingredient->outletStoreItem;

                        if ($outletStoreItem) {
                            $restoreQty = $orderedQty * $ingredient->pivot->quantity_needed;
                            $outletStoreItem->qty += $restoreQty;
                            $outletStoreItem->save();
                        }
                    }
                }
            }
            // Regular menu items
            else {
                foreach ($menuItem->ingredients as $ingredient) {
                    $outletStoreItem = $ingredient->outletStoreItem;

                    if ($outletStoreItem) {
                        $restoreQty = $orderedQty * $ingredient->pivot->quantity_needed;
                        $outletStoreItem->qty += $restoreQty;
                        $outletStoreItem->save();
                    }
                }
            }
        }
    }
}
