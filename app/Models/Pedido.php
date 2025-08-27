<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'numero_pedido',
        'origen',
        'cliente_id',
        'fecha_entrega',
        'estado',
    ];

    public function lineas()
    {
        return $this->hasMany(PedidoLinea::class);
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPedido::class, 'estado_pedido_id');
    }


}
