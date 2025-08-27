<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse',
        'store',
        'quantity',
        'lote',
        'fecha_caducidad',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
