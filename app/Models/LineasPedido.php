<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineasPedido extends Model
{
    protected $fillable = ['pedido_id','product_id','cantidad_total','cantidad_revisada','cantidad_asignada_manual','observaciones'];
      
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
