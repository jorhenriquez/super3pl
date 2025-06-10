<?php

namespace App\Imports;

use App\Models\Pedido;
use App\Models\LineasPedido;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LineasDesdeExcelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $referencia = $row['referencia']; // Asegúrate que el nombre es así en el Excel

            // 1. Crear o buscar pedido
            $pedido = Pedido::firstOrCreate(
                ['referencia' => $referencia],
                [
                    'idDestino' => $row['cliente'],
                    'destino' => $row['nombre'] ?? 'Sin nombre',
                    'direccion' => $row['direccion'] ?? '',
                    'comuna' => $row['comuna'] ?? '',
                    'cantidad' => 0,
                    'estado_pedido_id' => 1,
                ]
            );

            // 2. Crear o buscar producto
            $producto = Product::firstOrCreate(
                ['codigo' => $row['material']],
                [
                    'descripcion' => $row['desc_producto'] ?? '',
                    'ean' => '', // Si no viene, queda vacío
                ]
            );

            // 3. Crear línea de pedido
            $cantidad = $row['cantidad'] ?? 1;

            LineasPedido::create([
                'pedido_id' => $pedido->id,
                'product_id' => $producto->id,
                'cantidad_total' => $cantidad,
                'cantidad_revisada' => 0,
            ]);

            // 4. Actualiza cantidad total del pedido
            $pedido->increment('cantidad', $cantidad);
        }
    }
}
