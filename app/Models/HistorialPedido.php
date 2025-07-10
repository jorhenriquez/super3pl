<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialPedido extends Model
{
    protected $fillable = ['pedido_id', 'user_id', 'tipo', 'descripcion'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
