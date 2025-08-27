<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AlerceService;
use App\Models\Recepcion;
use App\Models\RecepcionLinea;
use App\Models\EstadoRecepcion;
use App\Models\PedidoLinea;
use App\Models\Product;
use Carbon\Carbon;

class SyncPedidosFromAlerce extends Command
{
    protected $signature = 'sync:actas';
    protected $description = 'Sincroniza actas desde Alerce (crear o actualizar estado)';

    public function handle(AlerceService $alerceService)
    {
        $actas = $alerceService->getActas();
   
        foreach ($actas as $actaAlerce) {
            $estado = EstadoRecepcion::firstOrCreate(
                    ['codigo' => $actaAlerce['estado']],
                    ['codigo' => $actaAlerce['estado'], 'descripcion' => ucfirst($actaAlerce['descripcion'])]
                );


            $recepcion = Recepcion::updateOrCreate(
                    // 🔑 Clave para buscar
                    ['numeroActa' => $actaAlerce['numeroActa']],
                    // 📝 Valores a actualizar o crear
                    [
                        'numeroActa' => $actaAlerce['numeroActa'],
                        'referencia' => $actaAlerce['referencia'] ?? 'Sin referencia en WMS',
                        'referencia2' => $actaAlerce['referencia2'] ?? 'Sin referencia2 en WMS',
                        'fechaAlta' => !empty($actaAlerce['fechaAlta'])
                            ? Carbon::createFromFormat('d/m/Y H:i:s', $actaAlerce['fechaAlta'])->format('Y-m-d H:i:s')
                            : null,
                        'fechaConfirmacion' => !empty($actaAlerce['fechaConfirmacion'])
                            ? Carbon::createFromFormat('d/m/Y H:i:s', $actaAlerce['fechaConfirmacion'])->format('Y-m-d H:i:s')
                            : null,
                        'fechaUbicacion' => !empty($actaAlerce['fechaUbicacion'])
                            ? Carbon::createFromFormat('d/m/Y H:i:s', $actaAlerce['fechaUbicacion'])->format('Y-m-d H:i:s')
                            : null,
                        'fechaBaja' => !empty($actaAlerce['fechaBaja'])
                            ? Carbon::createFromFormat('d/m/Y H:i:s', $actaAlerce['fechaBaja'])->format('Y-m-d H:i:s')
                            : null,
                        'estado_recepcion_id'        => $estado->id,
                    ]
                );

            $this->info("Pedido {$actaAlerce['numeroActa']} creado.");
        }

        $this->info("Sincronización de pedidos completada.");
    }
}

