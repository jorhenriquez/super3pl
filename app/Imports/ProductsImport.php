<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     */
    public function model(array $row)
    {
        return new Product([
            'wms_code'      => $row['cod_wms'],      // nombre de la columna en Excel
            'internal_code' => $row['cod_cliente'],  // nombre de la columna en Excel
            'name'          => $row['nombre'],       // nombre de la columna en Excel
        ]);
    }
}
 