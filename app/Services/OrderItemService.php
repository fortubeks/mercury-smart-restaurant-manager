<?php

namespace App\Services;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\MenuItemOrder;
use App\Models\OutletPreparedMenuItem;

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

            if (restaurant()->appSetting->inventory_style === 'prepared') {
                // For prepared items, reduce from prepared stock
                if ($menuItem->is_combo) {
                    // For combo items, deduct stock for each component
                    foreach ($menuItem->components as $component) {
                        $preparedItem = OutletPreparedMenuItem::where('outlet_id', $order->outlet_id)
                            ->where('menu_item_id', $component->id)->first();
                        if ($preparedItem) {
                            $deductQty = $item['quantity'] * $component->pivot->qty;
                            $preparedItem->decrement('qty', $deductQty);
                        }
                    }
                } else {
                    // For regular items, deduct stock directly
                    $preparedItem = OutletPreparedMenuItem::where('outlet_id', $order->outlet_id)
                        ->where('menu_item_id', $itemId)->first();
                    if ($preparedItem) {
                        $preparedItem->decrement('qty', $item['quantity']);
                    }
                }
            } else {
                // For regular items, deduct ingredient stock
                $this->deductIngredientStock($menuItem, $item['quantity']);
            }

            // // Handle combo menu items
            // if ($menuItem->is_combo) {
            //     foreach ($menuItem->components as $component) {
            //         foreach ($component->ingredients as $ingredient) {
            //             $outletStoreItem = $ingredient->outletStoreItem;

            //             if ($outletStoreItem) {
            //                 $deductQty = $item['quantity'] * $ingredient->pivot->quantity_needed;
            //                 $outletStoreItem->qty -= $deductQty;
            //                 $outletStoreItem->save();
            //             }
            //         }
            //     }
            // }
            // // Handle regular menu items
            // else {
            //     if ($menuItem->ingredients->isNotEmpty()) {
            //         foreach ($menuItem->ingredients as $ingredient) {
            //             $outletStoreItem = $ingredient->outletStoreItem;

            //             if ($outletStoreItem) {
            //                 $deductQty = $item['quantity'] * $ingredient->pivot->quantity_needed;
            //                 $outletStoreItem->qty -= $deductQty;
            //                 $outletStoreItem->save();
            //             }
            //         }
            //     }
            // }
        }
    }

    public function deductIngredientStock(MenuItem $menuItem, int $orderedQty)
    {
        if (!restaurant()->appSetting->manage_stock) {
            return;
        }

        // Combo menu items
        if ($menuItem->is_combo) {
            foreach ($menuItem->components as $component) {
                foreach ($component->ingredients as $ingredient) {
                    $outletStoreItem = $ingredient->outletStoreItem;

                    if ($outletStoreItem) {
                        $deductQty = $orderedQty * $ingredient->pivot->quantity_needed;
                        $outletStoreItem->qty -= $deductQty;
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
                    $deductQty = $orderedQty * $ingredient->pivot->quantity_needed;
                    $outletStoreItem->qty -= $deductQty;
                    $outletStoreItem->save();
                }
            }
        }
    }

    public function restoreItemsAndStock(Order $order)
    {
        if (!restaurant()->appSetting->manage_stock) {
            return;
        }

        if (restaurant()->appSetting->inventory_style === 'prepared') {
            // For prepared items, reduce from prepared stock
            foreach ($order->menuItems as $item) {
                $itemId = $item->id;

                if ($item->is_combo) {
                    // For combo items, restore stock for each component
                    foreach ($item->components as $component) {
                        $preparedItem = OutletPreparedMenuItem::where('outlet_id', $order->outlet_id)
                            ->where('menu_item_id', $component->id)->first();
                        if ($preparedItem) {
                            $restoreQty = $item->pivot->qty * $component->pivot->qty;
                            $preparedItem->increment('qty', $restoreQty);
                        }
                    }
                    continue;
                }
                $preparedItem = OutletPreparedMenuItem::where('outlet_id', $order->outlet_id)
                    ->where('menu_item_id', $itemId)->first();
                if ($preparedItem) {
                    $preparedItem->decrement('qty', $item['quantity']);
                }
            }
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
