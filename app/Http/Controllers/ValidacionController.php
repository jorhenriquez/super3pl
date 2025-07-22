<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Ingreso;
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
                ->whereHas('estado_pedido', function ($query) {
                    $query->whereIn('nombre', ['En revision']);
                })
                ->first();

        if ($pedidoEnRevision) {
            // Redirige directo a validar ese pedido
            return redirect()->route('validacion.validar', $pedidoEnRevision);
        }

        // Si no, mostrar pedidos en estado "Asignado"
        $pedidos = Pedido::with('lineas.product')
                ->whereHas('estado_pedido', function ($q) {
                    $q->whereIn('nombre', ['Asignado', 'En proceso']);
                })
                ->where(function ($q) use ($user) {
                    $q->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
                })
                ->get();


        // INGRESOS

        $ingresoEnRevision = Ingreso::where('user_id', $user->id)
                ->whereHas('estado_pedido', function ($query) {
                    $query->whereIn('nombre', ['En revision']);
                })
                ->first();

        if ($ingresoEnRevision) {
            // Redirige directo a validar ese pedido
            return redirect()->route('validacion.validar.ingreso', $ingresoEnRevision);
        }

        // Si no, mostrar pedidos en estado "Asignado"
        $ingresos = Ingreso::with('lineas.product')
                ->whereHas('estado_pedido', function ($q) {
                    $q->whereIn('nombre', ['Asignado', 'En proceso']);
                })
                ->where(function ($q) use ($user) {
                    $q->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
                })
                ->get();


        return view('validacion.index', compact('pedidos','ingresos'));
    }


    public function validar(Pedido $pedido)
    {
        if ($pedido->estado_pedido->nombre === 'Asignado' || $pedido->estado_pedido->nombre === 'En proceso') {
            $pedido->estado_pedido_id = EstadoPedido::where('nombre', 'En revision')->first()->id;
            $pedido->save();
        }

        // 🔁 Cargar líneas y productos asociados
        $pedido->load('lineas.product');

        return view('validacion.validar', compact('pedido'));
    }

    public function validar_ingreso(Ingreso $ingreso)
    {
        if ($ingreso->estado_pedido->nombre === 'Asignado') {
            $ingreso->estado_pedido_id = EstadoPedido::where('nombre', 'En revision')->first()->id;
            $ingreso->save();
        }

        // 🔁 Cargar líneas y productos asociados
        $ingreso->load('lineas.product');

        return view('validacion.validar_ingreso', compact('ingreso'));
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
            $pedido->estado_pedido_id = EstadoPedido::where('nombre', 'En proceso')->first()->id;
            $pedido->save();    
            return redirect()->route('validacion.index')->with('status', 'Pedido no se ha finalizado.');
        }
    
        $estado = EstadoPedido::where('nombre', 'Revisado')->first();
        $pedido->estado_pedido_id = $estado->id;
        $pedido->save();
    
        return redirect()->route('validacion.index')->with('status', 'El pedido fue validado y finalizado exitosamente.');
    }

    public function finalizar_ingreso(Ingreso $ingreso)
    {
        $lineasIncompletas = $ingreso->lineas()
            ->whereColumn('cantidad_valida', '<', 'cantidad_total')
            ->exists();
    
        if ($lineasIncompletas) {
            $ingreso->estado_pedido_id = EstadoPedido::where('nombre', 'En proceso')->first()->id;
            $ingreso->save();    
            return redirect()->route('validacion.index')->with('status', 'Pedido no se ha finalizado.');
        }
    
        $estado = EstadoPedido::where('nombre', 'Revisado')->first();
        $ingreso->estado_pedido_id = $estado->id;
        $ingreso->save();
    
        return redirect()->route('validacion.index')->with('status', 'El ingreso fue validado y finalizado exitosamente.');
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
        ->whereHas('product', function ($query) use ($codigo) {
            $query->where('codigo', $codigo)
                  ->orWhere('ean', 'like', "%{$codigo}%");
        })
        ->first();

        if (!$linea) {
            RegistroErrorValidacion::create([
                'user_id' => Auth::id(),
                'pedido_id' => $pedido->id,
                'codigo_ingresado' => $codigo,
                'mensaje_error' => 'Producto no pertenece al pedido',
                'error_type_id' => 2,
            ]);
            
            return response()->json(
                    ['status' => 'error_val',
                    'message' => 'No existe este producto en este pedido',    
                ], 400);
        }
            
        if ($linea->cantidad_revisada >= $linea->cantidad_total) {
            RegistroErrorValidacion::create([
                'user_id' => Auth::id(),
                'pedido_id' => $pedido->id,
                'codigo_ingresado' => $codigo,
                'mensaje_error' => 'Linea revisada completa',
                'error_type_id' => 2,
            ]);
            
            return response()->json(
                    ['status' => 'error_val',
                    'message' => 'Linea revisada completa.',
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

    public function validarProductoIngreso(Request $request, Ingreso $ingreso)
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
            
    
        $linea = $ingreso->lineas()
        ->whereHas('product', function ($query) use ($codigo) {
            $query->where('codigo', $codigo)
                  ->orWhere('ean', 'like', "%{$codigo}%");
        })
        ->first();

        if (!$linea) {
            return response()->json(
                    ['status' => 'error_val',
                    'message' => 'No existe este producto en este pedido',    
                ], 400);
        }
            
        if ($linea->cantidad_valida >= $linea->cantidad_total) {
            
            return response()->json(
                    ['status' => 'error_val',
                    'message' => 'Linea revisada completa.',
                ], 400);
        }
          
    
        $linea->increment('cantidad_valida');

        $completas = $ingreso->lineas()->whereColumn('cantidad_total', '>', 'cantidad_valida')->count() === 0;
    
        return response()->json([
                'status' => 'success_val',
                'message' => 'Producto validado correctamente',
                'lineas_ingreso' => $ingreso->lineas()->with('product')->get(), // 👈 esto es clave
                'last_validated_id' => $linea->id, // si quieres resaltar o mover
            ]);

    }

    public function validarPallet(Ingreso $ingreso)
    {
        $lineas = $ingreso->lineas()
            ->with('product')
            ->whereHas('product', function ($query) {
                $query->whereNotNull('cantidad_palet')
                    ->where('cantidad_palet', '>', 0);
            })
            ->get()
            ->filter(function ($linea) {
                return ($linea->cantidad_total - $linea->cantidad_valida) >= ($linea->product->cantidad_palet ?? 0);
            })
            ->values(); // Reindexar la colección para el front-end

        return view('validacion.validar-palet', [
            'ingreso' => $ingreso,
            'lineas' => $lineas,
            'last_validated_id' => null
        ]);
    }


    public function validarPalletProducto(Request $request, Ingreso $ingreso)
    {
        $request->validate([
            'codigo' => 'required|string',
        ]);

        // Buscar la línea por código o EAN
        $linea = $ingreso->lineas()
            ->with('product')
            ->whereHas('product', fn($q) =>
                $q->where('codigo', $request->codigo)
                ->orWhere('ean', $request->codigo)
            )->first();

        if (!$linea) {
            return response()->json(['message' => 'Producto no encontrado en este ingreso.'], 422);
        }

        // Validar pallet
        $cantidadPallet = $linea->product->cantidad_palet ?? 0;
        if ($cantidadPallet <= 0) {
            return response()->json(['message' => 'El producto no tiene cantidad de pallet definida.'], 422);
        }

        // Verificar límite
        if ($linea->cantidad_valida + $cantidadPallet > $linea->cantidad_total) {
            return response()->json(['message' => 'No puedes validar más de la cantidad total.'], 422);
        }

        // Actualizar cantidad validada
        $linea->cantidad_valida += $cantidadPallet;
        $linea->save();

        return response()->json([
            'status' => 'success',
            'message' => "Se validó un pallet de {$cantidadPallet} unidades.",
            'lineas_ingreso' => $ingreso->lineas()->with('product')->get(),
        ]);
    }



}
