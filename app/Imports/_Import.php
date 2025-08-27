<?php

namespace App\Imports;

use App\Models\EstadoPedido;
use App\Models\LineaIngreso;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;

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
            'referencia' => $row['entrega'],
            'user_id' => Auth::id(), // Asocia al usuario autenticado
            'estado_pedido_id' => EstadoPedido::where('nombre', 'Creado')->first()->id, // Estado por defecto
        ]);
    }
}
