<?php

namespace App\Models;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    protected $fillable = ['nombre', 'cod_meribia', 'cod_externo', 'rut'];
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

}
