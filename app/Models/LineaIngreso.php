<?php

namespace App\Models;

use App\Models\Ingreso;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class LineaIngreso extends Model
{
    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
