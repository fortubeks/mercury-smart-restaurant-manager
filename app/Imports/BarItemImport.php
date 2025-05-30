<?php

namespace App\Imports;

use App\Models\BarItem;
use App\Models\RestaurantItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;

class RestaurantItemImport implements ToModel, WithStartRow, WithEvents
{
    public $outlet_id;
    public $importedItemCount = 0;

    public function __construct()
    {
        $this->outlet_id = auth()->user()->userAccount->hotel->defaultBar()->id;
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
        $this->importedItemCount++;
        return new BarItem([
            'name' => $row[0],
            'price' => $row[1],
            'outlet_id' => $this->outlet_id,
            'description' => $row[2],
            'is_available' => $row[3],
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
}
