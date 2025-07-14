<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoObservacion extends Model
{
    public $fillable = [
        'nombre',
    ];
    
    public function motivo()
    {
        return $this->hasMany(LineasPedido::class, 'motivo_observacion_id');
    }

}
