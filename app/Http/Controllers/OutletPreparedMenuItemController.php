<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MenuItem;
use App\Models\OutletPreparedMenuItem;
use App\Services\OrderItemService;
use Illuminate\Support\Facades\DB;

class OutletPreparedMenuItemController extends Controller
{
    public function create()
    {
        if (restaurant()->appSetting->inventory_style !== 'prepared') {
            abort(403);
        }

        $menuItems = MenuItem::where('outlet_id', outlet()->id)->where('is_combo', false)
            ->orderBy('name')
            ->get();

        return theme_view('menu-items.prepared-items.create', compact('menuItems'));
    }

    public function store(Request $request, OrderItemService $orderItemService)
    {
        if (restaurant()->appSetting->inventory_style !== 'prepared') {
            abort(403);
        }

        $request->validate([
            'menu_item_id' => ['required', 'exists:menu_items,id'],
            'qty' => ['required', 'numeric', 'gt:0'],
        ]);

        $outletId = outlet()->id;

        DB::transaction(function () use ($request, $outletId, $orderItemService) {

            $row = OutletPreparedMenuItem::firstOrCreate(
                [
                    'outlet_id'    => $outletId,
                    'menu_item_id' => $request->menu_item_id,
                ],
                [
                    'qty' => 0
                ]
            );

            $row->increment('qty', $request->qty);

            //deduct stock
            $menuItem = MenuItem::with(['components.ingredients.outletStoreItem', 'ingredients.outletStoreItem'])
                ->find($request->menu_item_id);
            if ($menuItem) {
                $orderItemService->deductIngredientStock($menuItem, $request->qty);
            }
        });

        return redirect()->back()->with('success', 'Prepared quantity added.');
    }
}
