<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroErrorValidacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pedido_id',
        'codigo_ingresado',
        'mensaje_error',
        'error_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
