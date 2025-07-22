<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
                'descripcion',
                'ean', 
                'codigo', 
                'ean_modificado', 
                'peso', 
                'volumen',
                'cantidad_palet', // Asegúrate de que este campo esté en tu migración
            ];

}
