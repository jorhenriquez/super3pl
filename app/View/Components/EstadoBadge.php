<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EstadoBadge extends Component
{
    public $estado;

    public function __construct($estado)
    {
        $this->estado = $estado;
    }

    public function render()
    {
        return view('components.estado-badge');
    }
}
