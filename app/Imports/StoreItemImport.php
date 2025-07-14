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
    public $storeId;
    public $importedItemCount = 0;
    public $errors = [];

    public function __construct($_storeId = null)
    {
        $_storeId = $_storeId ?? optional(auth()->user()->userAccount->restaurant->defaultStore)->id;

        $this->storeId = is_numeric($_storeId) ? (int) $_storeId : null;

        if (is_null($this->storeId)) {
            throw new \InvalidArgumentException('A valid store ID must be provided.');
        }
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
        return \DB::connection()->transaction(function () use ($row) {
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

            $qty = $row[4] ?? 0; // Default to 0

            //sync the store item with the store
            \Log::info('Syncing store item to store', [
                'store_id' => $this->storeId,
                'qty' => $qty,
                'row_4' => $row[4],
                'store_item_id' => $storeItem->id
            ]);
            $storeItem->stores()->syncWithoutDetaching([
                $this->storeId => [
                    'qty' => $qty, // Default to 0
                    'unit_cost' => $row[7] ?? 0, // Default to 0
                    'batch_reference' => $row[9] ?? null, // Optional batch number
                    'expiry_date' => $row[10] ?? null, // Optional expiry date
                ]
            ]);

            //if qty is more than 0 create new purchase record
            if ((float) $row[4] > 0) {
                (new PurchaseStoreService)->create([
                    'store_id' => $this->storeId,
                    'restaurant_id' => restaurantId(),
                    'purchase_date' => now()->toDateString(),
                    'sub_total' => 0,
                    'total_amount' => 0,
                    'items' => [[
                        'store_item_id' => $storeItem->id,
                        'qty' => $qty, // Default to 0
                        'received' => $qty, // Default to 0
                        'rate' => 0,
                        'sub_total' => 0 * $qty, // Default to 0
                        'total_amount' => 0 * $qty, // Default to 0
                        'unit_qty' => 0,
                        'description' => 'Initial stock on creation'
                    ]]
                ]);
            }

            return $storeItem;
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

    public function getErrors(): array
    {
        return $this->errors;
    }
}
