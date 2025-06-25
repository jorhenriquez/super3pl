<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Tabla extends Component
{
    public $headers;
    public $rows;
    public $fields;

    public function __construct($headers, $rows, $fields)
    {
        $this->headers = $headers;
        $this->rows = $rows;
        $this->fields = $fields;
    }

    public function render()
    {
        return view('components.tabla');
    }
}
