<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AlerceService;
use App\Models\Product;
use App\Models\Inventory;
use Carbon\Carbon;

class SyncStockFromAlerce extends Command
{
    protected $signature = 'sync:stock';
    protected $description = 'Sincroniza el stock desde AlerceService';

    public function handle(AlerceService $alerceService)
    {
        $data = $alerceService->getStock();
        
        if ($data['codigo'] == 3){
            $this->error("No hay nada que exportar");
            return 1;
        }

        if ($data['codigo'] == 0) {
            $this->info("Borrando stock actual");
            Inventory::truncate();
        }

        $stocks = $data['datos'];

        foreach ($stocks as $item) {
            $product = Product::firstOrCreate(
                [
                    'wms_code' => $item['articulo'],
                ],
                ['name' => '', 'internal_code' => $item['articulo']]
            );

            $fecha_caducidad = null;
            if ($item['fechaCaducidad'])
                $fecha_caducidad = Carbon::createFromFormat('d/m/Y', $item['fechaCaducidad'])->format('Y-m-d') ?? null;

            Inventory::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'warehouse' => $item['deposito'],
                    'store'     => $item['almacen'],
                    'lote'      => $item['lote'],
                    'fecha_caducidad' => $fecha_caducidad,
                ],
                ['quantity' => $item['cantidad']]
            );
        }

        $this->info("Stock sincronizado correctamente.");
    }
}
