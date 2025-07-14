<?php

namespace App\Imports;

use App\Models\StoreItem;
use App\Models\StoreItemCategory;
use App\Services\PurchaseStoreService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;

class StoreItemImport implements ToModel, WithStartRow, WithEvents
{
    public $store_id;
    public $importedItemCount = 0;
    public $errors = [];

    public function __construct($store_id = null)
    {
        // If store_id is provided, use it; otherwise, set it to the default store ID
        $this->store_id = $store_id ?? auth()->user()->userAccount->restaurant->defaultStore->id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function startRow(): int
    {
        // Specify the row number to start the import from
        return 2; // Skip the first row (header row)
    }
    public function model(array $row)
    {
        // Validate required columns
        if (is_null($row[0])  || is_null($row[2]) || is_null($row[3]) || is_null($row[4])) {
            $this->errors[] = "Row " . ($this->importedItemCount + 2) . " has missing required fields.";
            return null; // Skip this row
        }

        $category = StoreItemCategory::firstOrCreate([
            'restaurant_id' => restaurantId(),
            'name' => ucfirst(strtolower($row[2]))
        ]);
        $code = generateUniqueItemCode();
        $this->importedItemCount++;

        $storeItem = new StoreItem([
            'name' => $row[0],
            'description' => $row[1],
            'item_category_id' => $category->id,
            'code' => $code,
            'unit_measurement' => $row[3],
            'for_sale' => $row[5],
            'low_stock_alert' => $row[6],
            'cost_price' => $row[7],
            'selling_price' => $row[8],
        ]);

        $storeItem->save(); // 🔑 Save first to generate an ID

        //sync the store item with the store
        $storeItem->stores()->syncWithoutDetaching([
            $this->store_id => [
                'qty' => $row[4] ?? 0, // Default to 0
                'unit_cost' => $row[7] ?? 0, // Default to 0
                'batch_reference' => $row[9] ?? null, // Optional batch number
                'expiry_date' => $row[10] ?? null, // Optional expiry date
            ]
        ]);

        //if qty is more than 0 create new purchase record
        if ((float) $row[4] > 0) {
            (new PurchaseStoreService)->create([
                'store_id' => $storeItem->store_id,
                'restaurant_id' => restaurantId(),
                'purchase_date' => now()->toDateString(),
                'sub_total' => 0,
                'total_amount' => 0,
                'items' => [[
                    'store_item_id' => $storeItem->id,
                    'qty' => $storeItem->qty,
                    'received' => $storeItem->qty,
                    'rate' => 0,
                    'sub_total' => 0 * $storeItem->qty,
                    'total_amount' => 0 * $storeItem->qty,
                    'unit_qty' => 0,
                    'description' => 'Initial stock on creation'
                ]]
            ]);
        }

        return $storeItem;
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

    public function getErrors(): array
    {
        return $this->errors;
    }
}
