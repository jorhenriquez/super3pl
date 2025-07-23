<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Ingreso;
use App\Models\Product;
use App\Models\LineaIngreso;
use Illuminate\Support\Facades\Auth;

class LineasIngresoDesdeExcelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row['cantidad_entregada'] == 0) {
                continue;
            }
            $referencia = $row['entrega']; // Asegúrate que el nombre es así en el Excel

            // 1. Crear o buscar pedido
            $ingreso = Ingreso::firstOrCreate(
                ['referencia' => $referencia],
                [
                    'user_id' => Auth::user()->id, // Asocia el usuario activo
                    'estado_pedido_id' => 1,
                ]
            );

            // 2. Crear o buscar producto
            $producto = Product::firstOrCreate(
                ['codigo' => $row['codigo']],
                [
                    'descripcion' => $row['desc_producto'] ?? '',
                    'ean' => '', // Si no viene, queda vacío
                ]
            );

            // 3. Crear línea de pedido
            $cantidad = $row['cantidad_entregada'] ?? 1;

            $linea = LineaIngreso::firstOrCreate(
                [
                    'ingreso_id' => $ingreso->id,
                    'product_id' => $producto->id,
                ],
                [
                    'ingreso_id' => $ingreso->id,
                    'product_id' => $producto->id,
                    'cantidad_total' => $cantidad,
                    'cantidad_valida' => 0, // Inicialmente no validada
                ]
            );

            // Si la línea ya existe, actualiza la cantidad total
            if (!$linea->wasRecentlyCreated) 
                $linea->increment('cantidad_total', $cantidad);
        }
    }
}
