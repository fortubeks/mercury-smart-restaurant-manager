<?php

namespace App\Imports;

use App\Models\OutletStoreItem;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;

class MenuItemUpdateImport implements ToModel, WithStartRow, WithEvents
{
    public $outlet_id;
    public $importedItemCount = 0;

    public function __construct()
    {
        $this->outlet_id = auth()->user()->outlet_id;
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
            // Update the MenuItem record

            $menuItem = MenuItem::find($row[0]);

            if ($menuItem) {
                $menuItem->update([
                    'name' => $row[3],
                    'description' => $row[4] ?? null,
                    'price' => $row[5] ?? 0,
                ]);
            }
            $this->importedItemCount++;

            return $menuItem;
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
