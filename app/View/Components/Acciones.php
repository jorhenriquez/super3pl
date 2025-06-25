<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Acciones extends Component
{
    public $row; // objeto actual (ej. Pedido)

    public function __construct($row)
    {
        $this->row = $row;
    }

    public function render()
    {
        return view('components.acciones');
    }
}
