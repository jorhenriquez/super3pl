<?php

namespace App\Models;

use App\Models\LineaIngreso;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    public $fillable = [
        'referencia',
        'cantidad',
        'user_id',
        'estado_pedido_id',
    ];
    public function lineas()
    {
        return $this->hasMany(LineaIngreso::class);
    }

    public function estado_pedido()
    {
        return $this->belongsTo(EstadoPedido::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
