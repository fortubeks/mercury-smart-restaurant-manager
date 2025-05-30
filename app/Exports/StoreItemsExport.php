<?php

namespace App\Exports;

use App\Models\StoreItem;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StoreItemsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return StoreItem::where('store_id', auth()->user()->hotel->store->id)->get();
    }

    /**
     * Define the headings for the Excel sheet.
     *
     * @return array
     */
    public function headings(): array
    {
        return Schema::getColumnListing((new StoreItem)->getTable());
    }
}
