<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoLinea extends Model
{
    protected $fillable = ['pedido_id', 'product_id', 'cantidad'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
