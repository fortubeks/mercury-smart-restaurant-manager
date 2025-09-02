<?php

namespace App\Imports;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;

class MenuItemImport implements ToModel, WithStartRow, WithEvents
{
    public $outlet_id;
    public $importedItemCount = 0;

    public function __construct()
    {
        $this->outlet_id = auth()->user()->outlet_id;
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

        $categoryId = null;
        if (array_key_exists(4, $row) && !is_null($row[4])) {
            $category = MenuCategory::firstOrCreate([
                'name' => ucfirst(strtolower($row[4])),
                'outlet_id' => $this->outlet_id
            ]);
            $categoryId = $category->id;
        }

        return new MenuItem([
            'name' => $row[0] ?? null,
            'price' => $row[1] ?? 0,
            'outlet_id' => $this->outlet_id,
            'description' => $row[2] ?? null,
            'is_available' => $row[3] ?? 1,
            'menu_category_id' => $categoryId,
            'is_combo' => $row[5] ?? 0,
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
