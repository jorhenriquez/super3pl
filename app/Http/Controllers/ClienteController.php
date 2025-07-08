<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('cod_meribia', 'like', "%{$search}%")
                ->orWhere('rut', 'like', "%{$search}%")
                ->orWhere('cod_externo', 'like', "%{$search}%");
            });
        }

        $clientes = $query->paginate(10)->withQueryString();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cod_meribia' => 'nullable|integer',
            'cod_externo' => 'nullable|string|max:50',
            'rut' => 'required|string|max:50',
        ]);

        Cliente::create($request->only(['nombre', 'cod_meribia', 'cod_externo', 'rut']));

        return redirect()->route('clientes.create')->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cod_meribia' => 'nullable|integer',
            'cod_externo' => 'nullable|string|max:50',
            'rut' => 'required|string|max:50',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->nombre = $request->nombre;
        $cliente->rut = $request->rut;
        $cliente->cod_meribia = $request->cod_meribia;
        $cliente->cod_externo = $request->cod_externo;
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Producto eliminado correctamente');
    }

    public function seleccionar(Request $request)
    {
        $request->validate([
            'cliente_id' => ['nullable', 'exists:clientes,id'],
        ]);

        session(['cliente_activo' => $request->cliente_id]);

        return back();
    }

}
