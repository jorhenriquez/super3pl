<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
    protected $fillable = [
        'numeroActa',
        'referencia',
        'referencia2',
        'fechaAlta',
        'fechaConfirmacion',
        'fechaUbicacion',
        'fechaBaja',
        'clienteOrigen',
        'centroOrigen',
        'delegacion',
        'deposito',
        'almacen',
        'claveMovimiento',
        'consignatario',
        'observaciones',
        'fechaLlegada',
        'matricula',
        'conductor',
        'nifConductor',
        'temperatura',
        'bultos',
        'volumen',
        'peso',
        'proveedor',
        'ordenCompra',
        'asn',
        'centroCoste',
        'estado_recepcion_id',
    ];

    public function lineas()
    {
        return $this->hasMany(RecepcionLinea::class,'recepcion_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoRecepcion::class,'estado_recepcion_id');
    }
}
