<?php

namespace App\Imports;

use App\Models\Pedido;
use App\Models\PedidoLinea;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PedidosImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Se asume que la primera fila es header
        $rows->skip(1)->each(function ($row) {
            $pedido = Pedido::firstOrCreate(
                ['numero_pedido' => $row[0]],
                [
                    'origen' => 'cliente',
                    'fecha_entrega' => \Carbon\Carbon::createFromFormat('d/m/Y', $row[1])->format('Y-m-d'),
                ]
            );

            $product = Product::firstOrCreate(
                ['wms_code' => $row[2]],
                ['name' => $row[3], 'internal_code' => $row[4] ?? null]
            );

            PedidoLinea::create([
                'pedido_id'  => $pedido->id,
                'product_id' => $product->id,
                'cantidad'   => $row[5],
            ]);
        });
    }
}
