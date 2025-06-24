<?php 

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'descripcion' => $row['descripcion'],
            'codigo' => $row['codigo'],
            'ean' => $row['ean'],
            'ean_modificado' => $row['ean_mod'],
        ]);
    }
}
