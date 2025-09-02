<?php

namespace App\Http\Controllers;

use App\Exports\MenuItemsExport;
use App\Imports\MenuItemImport;
use App\Imports\MenuItemUpdateImport;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\OutletStoreItem;
use App\Models\StoreItem;
use App\Services\MenuItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MenuItemController extends Controller
{
    public function __construct(protected MenuItemService $menuItemService) {}

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

        $item = $this->menuItemService->save($request);

        if (!$item) {
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
        $storeItems = StoreItem::whereHas('category', function ($query) {
            $query->where('name', 'Food')->orWhere('name', 'Drinks');
        })->orderBy('name', 'asc')->get();
        $menuItems = MenuItem::where('outlet_id', outlet()->id)->where('is_combo', false)->orderBy('name', 'asc')->get();
        return theme_view('menu-items.form')->with(compact('menuItems', 'storeItems', 'menuItem'));
    }

    public function update(Request $request, $id)
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

        $menuItem = MenuItem::findOrFail($id);

        $updated = $this->menuItemService->update($request, $menuItem);

        if (!$updated) {
            return redirect()->back()->with('error_message', 'Unable to update item');
        }

        return redirect()->route('menu-items.index')->with('success_message', 'Item updated successfully');
    }

    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        if ($menuItem->orders()->exists()) {
            return back()->with('error', 'Item cannot be deleted because they are linked to orders.');
        }

        $menuItem->delete();
        return redirect()->route('menu-items.index')->with('success_message', 'Item Deleted Successfully');
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

    private function saveOrUpdateMenuItem(Request $request, MenuItem $item = null): MenuItem
    {
        $requestData = [];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu-items/images', 'public');
            $requestData['image'] = $imagePath;
        }

        $requestData['is_available'] = $request->has('is_available') ? 1 : 0;
        $requestData['is_combo'] = $request->has('is_combo') ? 1 : 0;

        $categoryId = $request->menu_category_id ?? MenuCategory::where('outlet_id', $request->outlet_id)
            ->where('is_default', true)
            ->value('id');
        $request->merge(['menu_category_id' => $categoryId]);

        if ($item) {
            $item->update(array_merge($request->all(), $requestData));
        } else {
            $item = MenuItem::create(array_merge($request->all(), $requestData));
        }

        // Sync outlet store items
        $storeItems = $request->input('store_items', []);
        $syncData = [];
        foreach ($storeItems as $outletStoreItemId => $values) {
            if (isset($values['checked']) && isset($values['quantity_used'])) {
                $syncData[$outletStoreItemId] = [
                    'quantity_used' => $values['quantity_used']
                ];
            }
        }
        $item->outletStoreItems()->sync($syncData);

        // Sync combo components
        $comboItems = $request->input('combo_items', []);
        $syncData = [];
        foreach ($comboItems as $componentId => $values) {
            if (isset($values['checked']) && $values['checked']) {
                $syncData[$componentId] = [
                    'quantity' => $values['quantity'] ?? 1
                ];
            }
        }
        $item->components()->sync($syncData);

        return $item;
    }

    public function showMappingForm()
    {
        $menuItems = MenuItem::where('outlet_id', outlet()->id)->where('is_combo', false)->get();
        //$outletStoreItems = OutletStoreItem::where('outlet_id', outlet()->id)->get();
        $storeItems = StoreItem::whereHas('category', function ($query) {
            $query->where('name', 'Food')->where('name', 'Drinks');
        })
            ->where('for_sale', true)
            ->get();
        return theme_view('menu-items.mapping-form')->with([
            'menuItems' => $menuItems,
            'storeItems' => $storeItems,
            'currentOutlet' => outlet(),
        ]);
    }

    public function mapMenuItems(Request $request)
    {
        $request->validate([
            'mappings' => 'required|array',
            'store_items' => 'array',
            'store_items.*.quantity_used' => 'nullable|numeric|min:0',
        ]);

        foreach ($request->input('mappings', []) as $menuItemId => $storeItemId) {
            $menuItem = MenuItem::find($menuItemId);
            $quantityUsed = $request->input("store_items.$menuItemId.quantity_used", 0);

            if (!$menuItem || !$storeItemId) continue;

            // Attach or update the pivot table
            $menuItem->ingredients()->syncWithoutDetaching([
                $storeItemId => ['quantity_needed' => $quantityUsed]
            ]);
        }

        return redirect()->route('menu-items.mapping-form')->with('success_message', 'Menu item mapping updated successfully.');
    }

    public function viewImportItemsForm()
    {
        //return form
        return theme_view('menu-items.import-form');
    }

    public function downloadSampleExcel()
    {
        $file = storage_path('sample-import-menu-items.csv'); // Replace 'sample.xlsx' with the path to your sample Excel file
        $headers = [
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->download($file, 'sample-import-menu-items.csv', $headers);
    }

    public function downloadExistingMenuItems()
    {
        return Excel::download(new MenuItemsExport, 'menu_items.xlsx');
    }

    public function importItems(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $file = $request->file('file');

        $import = new MenuItemImport();
        Excel::import($import, $file);

        $importedItemCount = $import->importedItemCount;
        return redirect('menu-items')->with('success_message', $importedItemCount . ' Items imported successfully');
    }

    public function importItemsByUpdate(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $file = $request->file('file');

        $import = new MenuItemUpdateImport();
        Excel::import($import, $file);

        $importedItemCount = $import->importedItemCount;
        return redirect('menu-items')->with('success_message', $importedItemCount . ' Items imported successfully');
    }
}
