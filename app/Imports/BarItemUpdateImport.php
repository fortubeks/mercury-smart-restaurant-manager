<?php

namespace App\Imports;

use App\Models\OutletStoreItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BarItemUpdateImport implements ToModel, WithStartRow, WithEvents
{
    public $outlet_id;
    public $importedItemCount = 0;

    public function __construct()
    {
        $this->outlet_id = userBarOutlet()->id;
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
            $this->importedItemCount++;

            // Update or create the corresponding StoreItem
            if (isset($row['store_item_id'])) {
                StoreItem::updateOrCreate(
                    ['id' => $row['store_item_id']],
                    [
                        'name' => $row['storeItem.name'] ?? null,
                        'cost_price' => $row['storeItem.cost_price'] ?? 0.00,
                        'selling_price' => $row['storeItem.selling_price'] ?? 0.00,
                    ]
                );
            }

            // Update or create the corresponding OutletStoreItem if a quantity is provided
            if (isset($row[0]) && $row[0] !== null) {
                $outlet_store_item = OutletStoreItem::updateOrCreate(
                    ['id' => $row[0]],
                    [
                        'store_item_id' => $row[2],
                        'qty' => $row[3] ?? 0,
                        'price' => $row[4] ?? 0.00,
                    ]
                );

                return $outlet_store_item;
            }

            return null; // Return null if no outlet store item is updated or created
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
