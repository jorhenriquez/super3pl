<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PedidosImport;

class PedidoController extends Controller
{


    public function index()
    {
        $pedidos = Pedido::with('lineas.product')->paginate(20);
        $estados = ['creado', 'enviado', 'recibido'];
        return view('pedidos.index', compact('pedidos','estados'));
    }

    public function show($id)
    {
        $pedido = Pedido::with('lineas.product')->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new PedidosImport, $request->file('file'));

        return redirect()->route('pedidos.index')->with('success', 'Pedidos importados correctamente.');
    }

}
