<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\OutletStoreItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::where('outlet_id', outlet()->id)->get();
        $outlet = outlet();
        return theme_view('menu-items.index')->with(['menuItems' => $menuItems, 'currentOutlet' => $outlet]);
    }

    public function create()
    {
        $outletStoreItems = OutletStoreItem::where('outlet_id', outlet()->id)->get();
        $menuItems = MenuItem::where('outlet_id', outlet()->id)->get();
        return theme_view('menu-items.form')->with(['menuItems' => $menuItems, 'outletStoreItems' => $outletStoreItems]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'outlet_id' => 'required|exists:outlets,id',
            'menu_category_id' => 'nullable|exists:menu_categories,id',
            'is_available' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu-items/images', 'public');
                $requestData['image'] = $imagePath;
            }

            $requestData['is_available'] = $request->has('is_available') ? 1 : 0;
            $requestData['is_combo'] = $request->has('is_combo') ? 1 : 0;

            $categoryId = $request->menu_category_id ?? MenuCategory::where('outlet_id', $request->outlet_id)->where('is_default', true)->value('id');

            $request->merge(['menu_category_id' => $categoryId]);

            $item = MenuItem::create(array_merge($request->all(), $requestData));

            //sync the outletStoreItem with the restaurant_item
            $data = $request->input('store_items', []);

            $syncData = [];

            foreach ($data as $outletStoreItemId => $values) {
                if (isset($values['checked']) && isset($values['quantity_used'])) {
                    $syncData[$outletStoreItemId] = [
                        'quantity_used' => $values['quantity_used']
                    ];
                }
            }

            $item->outletStoreItems()->sync($syncData);
            //end of sync

            //sync combo items
            $syncData = [];

            if (request()->has('combo_items')) {
                foreach (request('combo_items') as $componentId => $values) {
                    if (isset($values['checked']) && $values['checked']) {
                        $syncData[$componentId] = [
                            'quantity' => $values['quantity'] ?? 1
                        ];
                    }
                }
            }

            // Sync the combo components with quantity_used
            $item->components()->sync($syncData);
            //end of sync
            DB::commit();
        } catch (\Exception $e) {
            logger($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error_message', 'Unable to create item');
        }

        return redirect()->route('menu-items.index')->with('success_message', 'Item created successfully');
    }

    public function show($id)
    {
        // Display the specified resource.
    }

    public function edit($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $outletStoreItems = OutletStoreItem::where('outlet_id', outlet()->id)->get();
        $menuItems = MenuItem::where('outlet_id', outlet()->id)->get();
        return theme_view('menu-items.form')->with(['menuItems' => $menuItems, 'outletStoreItems' => $outletStoreItems, 'menuItem' => $menuItem]);
    }

    public function update(Request $request, $id)
    {
        // Update the specified resource in storage.
    }

    public function destroy($id)
    {
        // Remove the specified resource from storage.
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $outletId = outlet()->id;

        $menuItems = MenuItem::where('outlet_id', $outletId)
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($menuItems);
    }

    public function getMenuItemsByCategory(Request $request, $categoryId)
    {
        $menuItems = MenuItem::where('menu_category_id', $categoryId)
            ->where('outlet_id', outlet()->id)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($menuItems);
    }
}
