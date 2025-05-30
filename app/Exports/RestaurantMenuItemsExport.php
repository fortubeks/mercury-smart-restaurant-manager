<?php

namespace App\Exports;

use App\Models\RestaurantItem;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RestaurantMenuItemsExport implements FromCollection, WithHeadings
{
    /**
     * Get the collection of menu items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return hotel()->defaultRestaurant()->restaurantItems()->get();
    }

    /**
     * Define the headings for the Excel sheet.
     *
     * @return array
     */
    public function headings(): array
    {
        $columns = Schema::getColumnListing((new RestaurantItem)->getTable());
        $columns[] = 'quantity';
        return $columns;
    }
}
