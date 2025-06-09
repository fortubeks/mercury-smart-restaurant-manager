<?php

namespace App\Imports;

use App\Models\StoreItem;
use App\Models\StoreItemCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;

class StoreItemImport implements ToModel, WithStartRow, WithEvents
{
    public $store_id;
    public $importedItemCount = 0;
    public $errors = [];

    public function __construct()
    {
        $this->store_id = auth()->user()->userAccount->restaurant->defaultStore->id;
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

        //$item_category_id = getItemCategoryId($row[2]);
        $category = StoreItemCategory::firstOrCreate([
            'restaurant_id' => restaurantId(),
            'name' => ucfirst(strtolower($row[2]))
        ]);
        $code = generateUniqueItemCode();
        $this->importedItemCount++;

        return new StoreItem([
            'name' => $row[0],
            'description' => $row[1],
            'store_id' => $this->store_id,
            'item_category_id' => $category->id,
            'code' => $code,
            'unit_measurement' => $row[3],
            'qty' => $row[4],
            'for_sale' => $row[5],
            'low_stock_alert' => $row[6],
            'cost_price' => $row[7],
            'selling_price' => $row[8],
        ]);
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
