<?php

namespace App\Http\Controllers;

use App\Models\LineasPedido;
use Illuminate\Http\Request;

class LineasPedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LineasPedido $lineasPedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LineasPedido $lineasPedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LineasPedido $lineasPedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LineasPedido $lineasPedido)
    {
        //
    }

    public function guardarObservacion(Request $request, LineasPedido $linea)
    {
        $request->validate([
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $linea->observaciones = $request->observaciones;
        $linea->save();

        return redirect()->back()->with('success', 'Observación guardada correctamente.');
    }

}
