<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    protected $fillable = ['nombre'];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }
}
