<?php

namespace App\Imports;

use App\Models\Apartment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ApartmentImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection): void
    {
        foreach ($collection as $row)
        {
            Apartment::create([
                "name" => $row["name"],
                "price" => $row["price"],
                "bedrooms" => $row["bedrooms"],
                "bathrooms" => $row["bathrooms"],
                "storeys" => $row["storeys"],
                "garages" => $row["garages"],
            ]);
        }
    }
}
