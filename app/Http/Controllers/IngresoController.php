<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\User;
use App\Models\Pedido;
use App\Models\EstadoPedido;
use Illuminate\Http\Request;
use App\Models\HistorialPedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = Auth::user();
        $query = Ingreso::query();

        // Filtrar por usuario si no es admin
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Filtro de búsqueda libre
        if ($request->filled('search_ingreso')) {
            $search = $request->input('search_ingreso');
            $query->where(function($q) use ($search) {
                $q->where('referencia', 'like', "%{$search}%")
                ->orWhereHas('estado_pedido', fn($subQ) => 
                    $subQ->where('nombre', 'like', "%{$search}%"));
            });
        }

        // Usar fecha actual si no viene fecha
        $fecha = $request->input('fecha') ?? now()->toDateString();
        $query->whereDate('created_at', $fecha);

        // Filtro por nombre de estado
        if ($request->filled('estado')) {
            $query->whereHas('estado_pedido', fn($q) =>
                $q->where('nombre', $request->estado)
            );
        }

        // Filtro por cliente si está activo en la sesión
        if (session('cliente_activo')) {
            $query->where('cliente_id', session('cliente_activo'));
        }

        $ingresos = $query->with(['user', 'estado_pedido'])
                        ->orderBy('idDestino')
                        ->orderBy('destino')
                        ->orderByDesc('cantidad')
                        ->paginate(10)
                        ->withQueryString();

        $estados = EstadoPedido::orderBy('nombre')->pluck('nombre');

        return view('ingresos.index', compact('ingresos', 'estados'));
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
    public function show(Ingreso $ingreso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingreso $ingreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingreso $ingreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingreso $ingreso)
    {
        //
    }
}
