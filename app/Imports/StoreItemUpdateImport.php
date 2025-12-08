<?php

namespace App\Imports;

use App\Models\StoreItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;

class StoreItemUpdateImport implements ToModel, WithStartRow, WithEvents
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
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
        if (is_null($row[0]) || is_null($row[4])) {
            $this->errors[] = "Row " . ($this->importedItemCount + 2) . " has missing required fields.";
            return null; // Skip this row
        }
        $storeItem = StoreItem::updateOrCreate(
            ['id' => $row[0]],
            [
                'name' => $row[3],
                'unit_measurement' => $row[7]
            ]
        );
        $this->importedItemCount++;

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
