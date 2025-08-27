<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecepcionLinea extends Model
{
    protected $fillable = [
        'recepcion_id',
        'product_id',
        'cantidad',
        'paletizacion',
        'peso',
        'volumen',
        'tipoMercancia',
        'tipoUnidad',
        'lote',
        'fechaCaducidad',
        'ssccCliente',
        'observaciones',
        'paletAlto',
        'tipoEmbalaje',
        'itemOrdenCompra',
        'posicionAsn',
        'centroCoste',
        'idBulto',
    ];

    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class,'recepcion_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
