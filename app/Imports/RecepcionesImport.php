<?php

namespace App\Imports;

use App\Models\Recepcion;
use App\Models\RecepcionLinea;
use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class RecepcionesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $rows->skip(1)->each(function ($row) {
            $recepcion = Recepcion::firstOrCreate(
                ['referencia' => $row[0]],
                [
                    'fecha'   => Carbon::createFromFormat('d/m/Y', $row[1])->format('Y-m-d'),
                    'bodega'  => $row[2],
                ]
            );

            $product = Product::firstOrCreate(
                ['wms_code' => $row[3]],
                ['name' => $row[4]]
            );

            RecepcionLinea::create([
                'recepcion_id' => $recepcion->id,
                'product_id'   => $product->id,
                'cantidad'     => $row[5],
            ]);
        });
    }
}

