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
    // public function saveItemsAndUpdateStock(Order $order, array $items)
    // {
    //     foreach ($items as $itemId => $item) {
    //         $orderItem = MenuItemOrder::create([
    //             'order_id' => $order->id,
    //             'menu_item_id' => $itemId,
    //             'qty' => $item['quantity'],
    //             'sub_total' => $item['total'],
    //             'tax_rate' => calculateTaxRate(),
    //             'tax_amount' => $item['tax'],
    //             'discount_rate' => 0,
    //             'discount_amount' => 0,
    //             'total_amount' => $item['total'],
    //         ]);

    //         if (!restaurant()->appSetting->manage_stock) {
    //             continue;
    //         }

    //         $menuItem = MenuItem::with(['components.outletStoreItem', 'outletStoreItems'])->find($itemId);

    //         if ($menuItem->is_combo) {
    //             foreach ($menuItem->components as $component) {
    //                 $outletStoreItem = $component->outletStoreItem;
    //                 if ($outletStoreItem) {
    //                     $outletStoreItem->qty -= $item['quantity'] * $component->pivot->quantity_used;
    //                     $outletStoreItem->save();
    //                 }
    //             }
    //         } elseif ($menuItem->outletStoreItems->isNotEmpty()) {
    //             foreach ($menuItem->outletStoreItems as $storeItem) {
    //                 $storeItem->qty -= $item['quantity'] * $storeItem->pivot->quantity_used;
    //                 $storeItem->save();
    //             }
    //         } else {
    //             $outletStoreItem = $menuItem->outletStoreItem ?? null;
    //             if ($outletStoreItem) {
    //                 $outletStoreItem->qty -= $item['quantity'];
    //                 $outletStoreItem->save();
    //             }
    //         }
    //     }
    // }
}
