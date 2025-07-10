<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pedido;
use App\Models\EstadoPedido;
use Illuminate\Http\Request;
use App\Models\HistorialPedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Pedido::query();

        // Filtrar por usuario si no es admin
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Filtro de búsqueda libre
        if ($request->filled('search_pedido')) {
            $search = $request->input('search_pedido');
            $query->where(function($q) use ($search) {
                $q->where('referencia', 'like', "%{$search}%")
                ->orWhere('destino', 'like', "%{$search}%")
                ->orWhere('direccion', 'like', "%{$search}%")
                ->orWhere('comuna', 'like', "%{$search}%")
                ->orWhere('cantidad', 'like', "%{$search}%")
                ->orWhereHas('estado_pedido', fn($subQ) => 
                        $subQ->where('nombre', 'like', "%{$search}%"));
            });
        }

        // Filtro por fecha de creación exacta
        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

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

        // Obtener los pedidos filtrados
        $pedidos = $query->with(['user', 'estado_pedido'])
                        ->orderBy('idDestino')
                        ->orderBy('destino')
                        ->orderByDesc('cantidad')
                        ->paginate(10)
                        ->withQueryString(); // mantiene los filtros en la paginación

        // Obtener lista de estados para el select
        $estados = EstadoPedido::orderBy('nombre')->pluck('nombre');

        return view('pedidos.index', compact('pedidos', 'estados'));
    }

    public function create()
    {
        return view('pedidos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'referencia' => 'required|integer',
            'idDestino' => 'required|string',
            'destino' => 'required|string|max:50',
            'direccion' => 'nullable|string|max:50',
            'comuna' => 'required|string|max:50',
            'cantidad' => 'required|integer',
            'estado' => 'nullable',
        ]);

        $pedido = Pedido::create([
            'referencia'        => $request->referencia,
            'idDestino'         => $request->idDestino,
            'destino'           => $request->destino,
            'direccion'         => $request->direccion,
            'comuna'            => $request->comuna,
            'cantidad'          => $request->cantidad,
            'estado_pedido_id'  => $request->estado,
            'cliente_id'        => session('cliente_activo'), // Aquí se asocia el cliente activo
        ]);


        return redirect()->route('pedidos.create')->with('success', 'Pedido creado correctamente.');
    }

    public function show(Pedido $pedido)
    {
        $pedido->load(['user', 'lineas.product']); // carga relaciones necesarias
        return view('pedidos.show', compact('pedido'));
    }

    public function edit($id)
    {
        $pedido = Pedido::findOrFail($id);
        return view('pedidos.edit', compact('pedido'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'referencia' => 'required|integer',
            'idDestino' => 'required|string',
            'destino' => 'required|string|max:50',
            'direccion' => 'nullable|string|max:50',
            'comuna' => 'required|string|max:50',
            'cantidad' => 'required|integer',
            'estado' => 'nullable',
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->referencia = $request->referencia;
        $pedido->idDestino = $request->idDestino;
        $pedido->destino = $request->destino;
        $pedido->direccion = $request->direccion;
        $pedido->comuna = $request->comuna;
        $pedido->cantidad = $request->cantidad;
        $pedido->estado = $request->estado;
        $pedido->save();

        return redirect()->route('pedidos.index')->with('success', 'Pedido actualizado correctamente');
    }

    public function destroy(Pedido $pedido)
    {
        $pedido->delete();

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido eliminado correctamente');
    }

    public function assign(Request $request, Pedido $pedido)
    {
        $search = $request->input('search');

        $usuarios = User::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10)->withQueryString();

        return view('pedidos.assign', compact('pedido', 'usuarios', 'search'));
    }

    public function updateAssign(Request $request, Pedido $pedido)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
        ]);

        $pedido->user_id = $request->usuario_id;
        $pedido->save();

        // Preserve search_pedido if present
        $search = $request->input('search_pedido');
        $params = [];
        if ($search) {
            $params['search_pedido'] = $search;
        }

        return redirect()->route('pedidos.index', $params)
            ->with('success', 'Usuario asignado correctamente');
    }

    public function upEstado(Pedido $pedido)
    {
        $pedido->estado_pedido_id++;
        $pedido->save();


        // Preserve search_pedido if present
        $search = request()->input('search_pedido');
        $params = [];
        if ($search) {
            $params['search_pedido'] = $search;
        }

        return redirect()->route('pedidos.index', $params)
            ->with('success', 'Pedido actualizado correctamente');
    }

    public function reasignar(Pedido $pedido)
    {
        if ($pedido->estado_pedido->nombre == "En revision") {
            $estado = EstadoPedido::where('nombre', 'Asignado')->first();
            $pedido->estado_pedido_id = $estado->id;

            foreach($pedido->lineas as $linea){
                $linea->cantidad_revisada = 0;
                $linea->save();
            }
            $pedido->save();

            return redirect()->back()->with('status', 'Pedido devuelto a estado Asignado.');
        }

        return redirect()->back()->with('error', 'El pedido no está en estado válido para reasignar.');
    }

    public function quitarUsuario(Pedido $pedido)
    {
        if ($pedido->estado_pedido->nombre == "Asignado" && $pedido->user_id) {
            $pedido->user_id = null;
            $pedido->estado_pedido_id = 1;
            $pedido->save();
            
            return redirect()->back()->with('status', 'Usuario eliminado del pedido.');
        }

        return redirect()->back()->with('error', 'No se puede eliminar el usuario en este estado.');
    }

    public function showImportForm()
    {
        return view('pedidos.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $path = $request->file('file')->store('imports');
        // Aquí puedes usar Laravel Excel o PHP para procesar el archivo
        // Ejemplo simple: solo guardar el archivo y mostrar mensaje
        return back()->with('success', 'Archivo subido correctamente: ' . $path);
    }

    public function finalizar(Pedido $pedido)
    {
        foreach ($pedido->lineas as $linea) {
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

        $pedido->estado_pedido_id = $estado->id;
        $pedido->save();

        return redirect()->route('pedidos.show', $pedido)->with('success', 'Pedido finalizado con observaciones.');
    }


}
