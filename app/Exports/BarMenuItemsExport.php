<?php

namespace App\Exports;

use App\Models\OutletStoreItem;
use App\Models\StoreItem;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarMenuItemsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $excludeColumns = ['created_at', 'updated_at', 'storeItem.qty','storeItem.store_id'];
    /**
     * Fetch the data for the Excel export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return OutletStoreItem::where('outlet_id', userBarOutlet()->id)
            ->with(['storeItem' => function($query) {
                $query->select('id', 'name', 'cost_price', 'selling_price'); // Select only the required columns
            }])
            ->get();
    }
    public function headings(): array
    {
        $outletStoreItemColumns = Schema::getColumnListing((new OutletStoreItem)->getTable());

        // Define custom columns for storeItem
        $storeItemColumns = ['storeItem.name', 'storeItem.cost_price', 'storeItem.selling_price'];

        // Merge columns
        $columns = array_merge($outletStoreItemColumns, $storeItemColumns);

        // Remove redundant or unwanted columns
        $columns = array_diff($columns, ['created_at', 'updated_at', 'store_item']);

        return $columns;
    }

    /**
     * Map the data for each row in the Excel sheet.
     *
     * @param mixed $outletStoreItem
     * @return array
     */
    public function map($outletStoreItem): array
    {
        // Get OutletStoreItem attributes
        $outletStoreItemAttributes = $outletStoreItem->toArray();

        // Decode the storeItem JSON data
        $storeItemData = json_decode($outletStoreItem->storeItem, true);

        // Extract the required fields
        $storeItemAttributes = [
            'storeItem.name' => $storeItemData['name'] ?? '',
            'storeItem.cost_price' => $storeItemData['cost_price'] ?? '',
            'storeItem.selling_price' => $storeItemData['selling_price'] ?? ''
        ];

        // Merge attributes
        $attributes = array_merge($outletStoreItemAttributes, $storeItemAttributes);

        // Remove unwanted columns if necessary
        unset($attributes['created_at'], $attributes['updated_at'], $attributes['store_item']);

        return $attributes;
    }
}
