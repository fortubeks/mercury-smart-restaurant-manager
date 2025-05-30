<?php

namespace App\Imports;

use App\Models\OutletStoreItem;
use App\Models\RestaurantItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;

class RestaurantItemUpdateImport implements ToModel, WithStartRow, WithEvents
{
    public $outlet_id;
    public $importedItemCount = 0;

    public function __construct()
    {
        $this->outlet_id = auth()->user()->userAccount->hotel->defaultRestaurant()->id;
    }
    public function startRow(): int
    {
        // Specify the row number to start the import from
        return 2; // Skip the first row (header row)
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            // Update the RestaurantItem record

            $restaurantItem = RestaurantItem::updateOrCreate(
                ['id' => $row[0]],
                [
                    'name' => $row[2],
                    'description' => $row[5] ?? null,
                    'price' => $row[4] ?? 0,
                    'updated_at' => now(),
                    'outlet_id' => userRestaurantOutlet()->id,
                    'store_item_id' => $row[9] ?? null,
                    'outlet_store_item_id' => $row[8] ?? null,
                ]
            );
            $this->importedItemCount++;


            //get the quantity depending on if the hotel is managing inventory
            $quantity = 0;
            if (hotel()->appSetting->restaurant_manage_stock == false) {
                $quantity = 100;
            }

            //if store item and quantity
            if ($row[9]) {
                $outlet_store_item = OutletStoreItem::updateOrCreate(
                    ['id' => $row[8]],
                    [
                        'store_item_id' => $row[9],
                        'outlet_id' => $restaurantItem->outlet_id,
                        'qty' => $row[12] ?? $quantity
                    ]
                );
                $restaurantItem->outlet_store_item_id = $outlet_store_item->id;
                $restaurantItem->save();
            }

            return $restaurantItem;
        });
    }
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                // Access the imported item count from the import class
                $this->importedItemCount = $event->getConcernable()->importedItemCount;
            },
        ];
    }
}
