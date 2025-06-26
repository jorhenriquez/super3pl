<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Imports\PedidosImport;
use App\Imports\LineasDesdeExcelImport;

class PedidoImportController extends Controller
{
    public function show()
    {
        return view('pedidos.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $lineasFile = $request->file('file');
        
        if (file_exists($lineasFile)) {
            Excel::import(new LineasDesdeExcelImport, $lineasFile);
            return redirect()->route('pedidos.index')->with('success', 'Pedidos importados correctamente.');
        } else {
            return redirect()->route('pedidos.index')->with('error', 'No existe archivo.');
        }
    }
}

