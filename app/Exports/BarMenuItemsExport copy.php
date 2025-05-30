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
    protected $excludeColumns = ['created_at', 'updated_at', 'storeItem.qty'];
    /**
     * Fetch the data for the Excel export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return OutletStoreItem::where('outlet_id', userBarOutlet()->id)
            ->with('storeItem')
            ->get();
    }

    public function headings(): array
    {
        $outletStoreItemColumns = Schema::getColumnListing((new OutletStoreItem)->getTable());
        $storeItemColumns = Schema::getColumnListing((new StoreItem)->getTable());

        // Remove 'qty' from storeItem columns
        $storeItemColumns = array_diff($storeItemColumns, ['qty']);

        // Merge columns and remove duplicates
        $columns = array_unique(array_merge($outletStoreItemColumns, $storeItemColumns));

        // Remove excluded columns
        $columns = array_diff($columns, ['created_at', 'updated_at']);

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

        // Get related StoreItem attributes
        $storeItemAttributes = $outletStoreItem->storeItem ? $outletStoreItem->storeItem->toArray() : [];

        // Remove 'qty' from StoreItem attributes
        unset($storeItemAttributes['qty']);

        // Combine attributes
        $attributes = array_merge($outletStoreItemAttributes, $storeItemAttributes);

        // Remove excluded columns
        $attributes = array_diff_key($attributes, array_flip(['created_at', 'updated_at']));

        return $attributes;
    }
}
