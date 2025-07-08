<?php

namespace App\Models;

use App\Models\LineaIngreso;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    public function lineas()
    {
        return $this->hasMany(LineaIngreso::class);
    }

    public function estado_pedido()
    {
        return $this->belongsTo(EstadoPedido::class);
    }

}
