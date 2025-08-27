<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use Illuminate\Http\Request;
use App\Imports\RecepcionesImport;
use App\Models\EstadoRecepcion;
use Maatwebsite\Excel\Facades\Excel;

class RecepcionController extends Controller
{
    public function index()
    {
        $recepciones = Recepcion::with('lineas.product','estado')->paginate(20);
        $estados = EstadoRecepcion::all();
        return view('recepciones.index', compact('recepciones', 'estados' ));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new RecepcionesImport, $request->file('file'));

        return redirect()->route('recepciones.index')->with('success', 'Recepciones importadas correctamente.');
    }
}
