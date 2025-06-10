<?php

namespace App\Imports;

use App\Models\Pedido;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PedidosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Pedido([
            'idDestino' => $row['cliente'],
            'referencia' => $row['referencia'],
            'destino' => $row['destino'],
            'direccion' => $row['direccion'],
            'comuna' => $row['comuna'],
            'cantidad' => $row['cantidad'],
            'estado_pedido_id' => 1, // Por defecto estado 1 si no viene
        ]);
    }
}
