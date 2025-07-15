<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LineasIngresoDesdeExcelImport;

class IngresoImportController extends Controller
{
    public function show()
    {
        return view('ingresos.import');
    }

    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $lineasFile = $request->file('file');
        
        if (file_exists($lineasFile)) {
            Excel::import(new LineasIngresoDesdeExcelImport, $lineasFile);
            return redirect()->route('ingresos.index')->with('success', 'Ingresos importados correctamente.');
        } else {
            return redirect()->route('ingresos.index')->with('error', 'No existe archivo.');
        }
    }
}
