<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\EstadoPedido;

class DashboardController extends Controller
{

public function resumen(Request $request)
{
    $fecha_inicio = $request->input('fecha_inicio');
    $fecha_fin = $request->input('fecha_fin');

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

    return view('dashboard.resumen-tabla', [
        'tabla' => $tabla,
        'estados' => $estados,
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin,
    ]);
}


}
