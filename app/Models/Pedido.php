<?php

namespace App\Models;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    
    protected $fillable = ['idDestino','referencia', 'destino', 'direccion', 'comuna', 'cantidad','estado_pedido_id', 'user_id','cliente_id'];

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

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }



}
