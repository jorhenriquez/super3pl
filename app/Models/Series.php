<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    
    public function recepcion_linea()
    {
        return $this->belongsTo(RecepcionLinea::class,'recepcion_linea_id');
    }
}
