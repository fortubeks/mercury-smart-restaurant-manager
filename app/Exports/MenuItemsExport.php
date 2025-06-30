<?php

namespace App\Exports;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MenuItemsExport implements FromCollection, WithHeadings
{
    /**
     * Get the collection of menu items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return MenuItem::where('outlet_id', outlet()->id)->get();
    }

    /**
     * Define the headings for the Excel sheet.
     *
     * @return array
     */
    public function headings(): array
    {
        $columns = Schema::getColumnListing((new MenuItem)->getTable());
        $columns[] = 'quantity';
        return $columns;
    }
}
