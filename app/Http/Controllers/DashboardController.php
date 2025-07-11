<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\EstadoPedido;
use App\Models\Product;

class DashboardController extends Controller
{

    public function resumen(Request $request)
    {
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        // Si no hay fechas, usar hoy
        if (!$fecha_inicio && !$fecha_fin) {
            $fecha_inicio = $fecha_fin = now()->toDateString();
        }

        $query = Pedido::with(['estado_pedido', 'user']);

        if ($fecha_inicio) {
            $query->whereDate('created_at', '>=', $fecha_inicio);
        }

        if ($fecha_fin) {
            $query->whereDate('created_at', '<=', $fecha_fin);
        }

        $pedidos = $query->get();

        $estados = EstadoPedido::orderBy('id')->get();

        // Usuarios con pedidos (más "No asignado")
        $usuarios = $pedidos->pluck('user')->filter()->unique('id')->sortBy('name')->values();
        $usuarios->push((object)['id' => null, 'name' => 'No asignado']);

        $tabla = [];

        foreach ($usuarios as $usuario) {
            $fila = [];

            foreach ($estados as $estado) {
                $count = $pedidos->filter(function ($pedido) use ($estado, $usuario) {
                    return $pedido->estado_pedido_id === $estado->id &&
                        optional($pedido->user)->id === $usuario->id;
                })->count();

                $fila[$estado->nombre] = $count;
            }

            $tabla[] = [
                'usuario' => $usuario->name,
                'id_usuario' => $usuario->id,
                'valores' => $fila,
            ];
        }

        $productosSinEan = Product::whereNull('ean')
            ->orWhere('ean', '')
            ->get();

        $productosSinEanIds = $productosSinEan->pluck('id');


        // Obtener ID del estado "Observaciones"
        $estadoObservaciones = EstadoPedido::where('nombre', 'Observaciones')->first();

        $productosObservados = collect();

        if ($estadoObservaciones) {
            $productosObservados = Pedido::with(['lineas.product'])
                ->where('estado_pedido_id', $estadoObservaciones->id)
                ->when($fecha_inicio, fn($q) => $q->whereDate('created_at', '>=', $fecha_inicio))
                ->when($fecha_fin, fn($q) => $q->whereDate('created_at', '<=', $fecha_fin))
                ->get()
                ->flatMap(function ($pedido) {
                    return $pedido->lineas->map(function ($linea) use ($pedido) {
                        return [
                            'referencia' => $pedido->referencia,
                            'codigo' => optional($linea->product)->codigo,
                            'descripcion' => optional($linea->product)->descripcion,
                            'observaciones' => $linea->observaciones,
                            'producto_id' => optional($linea->product)->id,
                        ];
                    });
                })->filter(fn($item) => $item['producto_id']); // quita líneas sin producto
        }

        return view('dashboard.resumen-tabla', [
            'tabla' => $tabla,
            'estados' => $estados,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'productosSinEan' => $productosSinEan, // o ->count() si solo quieres el número
            'productosObservados' => $productosObservados,
            'productosSinEanIds' => $productosSinEanIds,
        ]);
    }


}
