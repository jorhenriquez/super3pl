<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AlerceService;
use App\Models\Pedido;
use App\Models\EstadoPedido;
use App\Models\PedidoLinea;
use App\Models\Product;
use Carbon\Carbon;

class SyncPedidosFromAlerce extends Command
{
    protected $signature = 'sync:pedidos';
    protected $description = 'Sincroniza pedidos desde Alerce (crear o actualizar estado)';

    public function handle(AlerceService $alerceService)
    {
        $pedidos = $alerceService->getPedidos();

        foreach ($pedidos as $pedidoAlerce) {
            $estado = EstadoPedido::firstOrCreate(
                    ['codigo' => $pedidoAlerce['estado']],
                    ['codigo' => $pedidoAlerce['estado'], 'descripcion' => ucfirst($pedidoAlerce['descripcion'])]
                );

            $pedido = Pedido::updateOrCreate(
                    // 🔑 Clave para buscar
                    ['albaran' => $pedidoAlerce['albaranWMS']],
                    // 📝 Valores a actualizar o crear
                    [
                        'numero_pedido' => $pedidoAlerce['referencia'],
                        'origen'        => 'wms',
                        'fecha_entrega' => !empty($pedidoAlerce['fechaAlta'])
                            ? Carbon::createFromFormat('d/m/Y H:i:s', $pedidoAlerce['fechaAlta'])->format('Y-m-d H:i:s')
                            : null,
                        'estado_pedido_id'        => $estado->id,
                    ]
                );

            $this->info("Pedido {$pedido->numero_pedido} creado.");
        }

        $this->info("Sincronización de pedidos completada.");
    }
}

