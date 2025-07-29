<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\User;
use App\Models\Pedido;
use App\Models\Ingreso;
use App\Models\EstadoPedido;
use Illuminate\Http\Request;
use App\Models\HistorialPedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exports\IngresoInformeExport;
use Maatwebsite\Excel\Facades\Excel;


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
        $ingreso->load(['user', 'lineas.product']); // carga relaciones necesarias
        return view('ingresos.show', compact('ingreso'));
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

    public function assign(Request $request, Ingreso $ingreso)
    {
        $search = $request->input('search');

        $usuarios = User::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10)->withQueryString();

        return view('ingresos.assign', compact('ingreso', 'usuarios', 'search'));
    }

    public function updateAssign(Request $request, Ingreso $ingreso)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
        ]);

        $ingreso->user_id = $request->usuario_id;
        $ingreso->save();

        // Preserve search_pedido if present
        $search = $request->input('search_ingreso');
        $params = [];
        if ($search) {
            $params['search_ingreso'] = $search;
        }

        return redirect()->route('ingresos.index', $params)
            ->with('success', 'Usuario asignado correctamente');
    }

    public function upEstado(Ingreso $ingreso)
    {
        $ingreso->estado_pedido_id = 2;
        $ingreso->save();


        // Preserve search_pedido if present
        $search = request()->input('search_ingreso');
        $params = [];
        if ($search) {
            $params['search_ingreso'] = $search;
        }

        return redirect()->route('ingresos.index', $params)
            ->with('success', 'Ingreso actualizado correctamente');
    }

    public function reasignar(Ingreso $ingreso)
    {
        if ($ingreso->estado_pedido->nombre == "En revision") {
            $estado = EstadoPedido::where('nombre', 'Asignado')->first();
            $ingreso->estado_pedido_id = $estado->id;

            foreach($ingreso->lineas as $linea){
                $linea->cantidad_revisada = 0;
                $linea->save();
            }
            $ingreso->save();

            return redirect()->back()->with('status', 'Ingreso devuelto a estado Asignado.');
        }

        return redirect()->back()->with('error', 'El ingreso no está en estado válido para reasignar.');
    }

    public function quitarUsuario(Ingreso $ingreso)
    {
        if ($ingreso->estado_pedido->nombre == "Asignado" && $ingreso->user_id) {
            $ingreso->user_id = null;
            $ingreso->estado_pedido_id = 1;
            $ingreso->save();
            
            return redirect()->back()->with('status', 'Usuario eliminado del ingreso.');
        }

        return redirect()->back()->with('error', 'No se puede eliminar el usuario en este estado.');
    }

    public function finalizar(Ingreso $ingreso)
    {
        foreach ($ingreso->lineas as $linea) {
            $cantidadTotal = $linea->cantidad_total;
            $cantidadRevisada = $linea->cantidad_revisada;
            $observacion = trim($linea->observaciones ?? '');

            if ($cantidadRevisada < $cantidadTotal && empty($observacion)) {
                
                return redirect()->back()->with('error', "La línea del producto '{$linea->product->descripcion}' tiene diferencia de cantidades sin observación.");
            }
        }

        $estado = EstadoPedido::where('nombre', 'Observaciones')->first();

        if (!$estado) {
            return redirect()->back()->with('error', 'Estado "Observaciones" no existe.');
        }

        $ingreso->estado_pedido_id = $estado->id;
        $ingreso->save();

        return redirect()->route('ingresos.show', $ingreso)->with('success', 'Pedido finalizado con observaciones.');
    }

    public function informe(Ingreso $ingreso)
    {
        $lineas = $ingreso->lineas->map(function($linea) {
            return [
                'codigo' => $linea->product->codigo,
                'descripcion' => $linea->product->descripcion,
                'cantidad_total' => $linea->cantidad_total,
                'cantidad_valida' => $linea->cantidad_valida,
                'faltante' => max(0, $linea->cantidad_total - $linea->cantidad_valida),
            ];
        });
        /*
        $pdf = PDF::loadView('ingresos.informe', [
            'ingreso' => $ingreso,
            'lineas' => $lineas,
        ]);*/

        return redirect()->route('ingresos.index');
    }

    public function exportExcel(Ingreso $ingreso)
    {
        return Excel::download(new IngresoInformeExport($ingreso), "informe_ingreso_{$ingreso->id}.xlsx");
    }


}
