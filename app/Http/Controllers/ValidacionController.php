<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\EstadoPedido;
use App\Models\LineasPedido;
use Illuminate\Http\Request;
use App\Models\HistorialPedido;
use App\Models\ErroresValidacion;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistroErrorValidacion;


class ValidacionController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        // Buscar si ya tiene un pedido en revisión
        $pedidoEnRevision = Pedido::where('user_id', $user->id)
            ->where('estado_pedido_id', 3) // "En revisión"
            ->first();

        if ($pedidoEnRevision) {
            // Redirige directo a validar ese pedido
            return redirect()->route('validacion.validar', $pedidoEnRevision);
        }

        // Si no, mostrar pedidos en estado "Asignado"
        $pedidos = Pedido::with('lineas.product')
            ->where('estado_pedido_id', 2)
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id')->orWhere('user_id', $user->id);
            })->get();


        return view('validacion.index', compact('pedidos'));
    }


    public function validar(Pedido $pedido)
    {
        if ($pedido->estado_pedido->nombre === 'Asignado') {
            $pedido->estado_pedido_id = EstadoPedido::where('nombre', 'En revision')->first()->id;
            $pedido->save();
        }

        // 🔁 Cargar líneas y productos asociados
        $pedido->load('lineas.product');

        return view('validacion.validar', compact('pedido'));
    }

    
    public function procesar(Request $request, Pedido $pedido)
    {
        $codigo = $request->input('codigo');
    
        $linea = $pedido->lineas()
            ->whereHas('product', function ($q) use ($codigo) {
                $q->where('codigo', $codigo)->orWhere('ean', $codigo);
            })->first();
    
        if (!$linea || $linea->cantidad_revisada >= $linea->cantidad_total) {
            return redirect()->back()->with('error', 'No existe este producto en este pedido');
        }
    
        $linea->increment('cantidad_revisada');
    
        return redirect()->back()->with('status', 'Éxito');
    }

    public function finalizar(Pedido $pedido)
    {
        $lineasIncompletas = $pedido->lineas()
            ->whereColumn('cantidad_revisada', '<', 'cantidad_total')
            ->exists();
    
        if ($lineasIncompletas) {
            // Error tipo 3: intento de finalizar sin revisar todo
            RegistroErrorValidacion::create([
                'user_id' => Auth::id(),
                'pedido_id' => $pedido->id,
                'mensaje_error' => 'Intento de finalizar pedido incompleto.',
                'error_type_id' => 3,
            ]);
    
            return redirect()->back()->with('error', 'No puedes finalizar: hay productos sin revisar completamente.');
        }
    
        $estado = EstadoPedido::where('nombre', 'Revisado')->first();
        $pedido->estado_pedido_id = $estado->id;
        $pedido->save();
    
        return redirect()->route('validacion.index')->with('status', 'El pedido fue validado y finalizado exitosamente.');
    }
    
    public function validarProducto(Request $request, Pedido $pedido)
    {
        $codigoEscaneado = $request->input('codigo');
        // Normaliza el código: si comienza con 1 o 2, lo elimina
        $codigo = substr($codigoEscaneado, 1,-1);


        $producto = Product::where('codigo', $codigo)
            ->orWhere('ean','like',"%{$codigo}%")
            ->first();
    
        if (!$producto) {
            $mensaje = 'Producto no encontrado';

            return response()->json(['status' => 'error_val', 'message' => $mensaje], 404);
        }
            
    
        $linea = $pedido->lineas()
            ->where('product_id', $producto->id)
            ->first();
    
        if (!$linea || $linea->cantidad_revisada >= $linea->cantidad_total) {
            RegistroErrorValidacion::create([
                'user_id' => Auth::id(),
                'pedido_id' => $pedido->id,
                'codigo_ingresado' => $codigo,
                'mensaje_error' => 'Producto no pertenece al pedido o ya está completamente revisado.',
                'error_type_id' => 2,
            ]);
            
            return response()->json(
                    ['status' => 'error_val',
                    'message' => 'No existe este producto en este pedido o ya está completo.',
                    
                ], 400);
        }
            
    
        $linea->increment('cantidad_revisada');
    
        $completas = $pedido->lineas()->whereColumn('cantidad_total', '>', 'cantidad_revisada')->count() === 0;
    
        return response()->json([
                'status' => 'success_val',
                'message' => 'Producto validado correctamente',
                'lineas_pedido' => $pedido->lineas()->with('product')->get(), // 👈 esto es clave
                'last_validated_id' => $linea->id, // si quieres resaltar o mover
            ]);

    }
}
