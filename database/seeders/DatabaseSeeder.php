<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Pedido;
use App\Models\PedidoLinea;
use App\Models\Product;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Jorge Henriquez',
            'email' => 'jhenriquez@supertrans.cl',
            'password' => bcrypt('12345678'),
        ]);

        Pedido::create([
            'numero_pedido' => 'PED-001',
            'origen' => 'cliente',
            'fecha_entrega' => now()->addDays(7),
        ]);

        Product::create([
            'name' => 'Producto A',
            'internal_code' => 'PROD-A',
            'wms_code' => 'WMS-A',
        ]);

        Product::create([
            'name' => 'Producto B',
            'internal_code' => 'PROD-B',
            'wms_code' => 'WMS-B',
        ]);

        PedidoLinea::create([
            'pedido_id' => 1,
            'product_id' => 1,
            'cantidad' => 10,
        ]);

        PedidoLinea::create([
            'pedido_id' => 1,
            'product_id' => 2,
            'cantidad' => 5,
        ]);
    }
}
