<?php

namespace App\Imports;

use App\Models\LineaIngreso;
use Maatwebsite\Excel\Concerns\ToModel;

class IngresoImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new LineaIngreso([
            //
        ]);
    }
}
