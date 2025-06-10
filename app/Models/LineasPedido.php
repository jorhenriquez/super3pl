<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineasPedido extends Model
{
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
