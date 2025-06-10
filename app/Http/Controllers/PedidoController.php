<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Filtro de búsqueda
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function($q) use ($search) {
                $q->where('referencia', 'like', "%{$search}%")
                ->orWhere('destino', 'like', "%{$search}%")
                ->orWhere('direccion', 'like', "%{$search}%")
                ->orWhere('comuna', 'like', "%{$search}%")
                ->orWhere('cantidad', 'like', "%{$search}%");
                // Nota: Si `estado` es una relación, este filtro no aplicará
            });
        }

        $pedidos = $query->with('user', 'estado_pedido')
                            ->orderBy('idDestino')
                            ->orderBy('destino')
                            ->orderByDesc('cantidad')
                            ->paginate(10)
                            ->withQueryString();

        return view('pedidos.index', compact('pedidos'));

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

        Pedido::create($request->only(['idDestino','referencia', 'destino', 'direccion', 'comuna', 'cantidad', 'estado']));

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

        return redirect()->route('pedidos.index')->with('success', 'Usuario asignado correctamente');
    }

    public function upEstado(Pedido $pedido)
    {
        $pedido->estado_pedido_id++;
        $pedido->save();

        return redirect()->route('pedidos.index')->with('success', 'Pedido actualizado correctamente');
    }

    public function reasignar(Pedido $pedido)
    {
        if ($pedido->estado_pedido->nombre == "En revision") {
            $pedido->estado_pedido_id = 2; // Estado "Asignado"
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


}
