<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    
    protected $fillable = ['idDestino','referencia', 'destino', 'direccion', 'comuna', 'cantidad','estado_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estado_pedido()
    {
        return $this->belongsTo(EstadoPedido::class);
    }

    public function lineas()
    {
        return $this->hasMany(LineasPedido::class);
    }


}
