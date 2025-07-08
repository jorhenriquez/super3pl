<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Product;
use App\Models\EstadoPedido;
use App\Models\LineasPedido;
use App\Imports\ProductsImport;
use Illuminate\Database\Seeder;
use Database\Seeders\ProductSeeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LineasDesdeExcelImport;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Jorge',
            'email' => 'jhenriquez@supertrans.cl',
            'password' => bcrypt('12345678'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Camila',
            'email' => 'chenriquez@supertrans.cl',
            'password' => bcrypt('12345678'),
            'role' => 'operador'
        ]);

        Cliente::create([
            'nombre' => 'Evercrisp Snacks S.A.',
            'rut' => '94.528.000-4',
            'cod_meribia' => 886,
        ]);

        $filePath = database_path('seeders/files/data_products_v2.xlsx');

        if (file_exists($filePath)) {
            Excel::import(new ProductsImport, $filePath);
            $this->command->info('Productos importados desde Excel correctamente.');
        } else {
            $this->command->warn('Archivo Excel de productos no encontrado. No se importaron productos.');
        }

        EstadoPedido::create(['nombre' => 'Creado']);
        EstadoPedido::create(['nombre' => 'Asignado']);
        EstadoPedido::create(['nombre' => 'En revision']);
        EstadoPedido::create(['nombre' => 'Revisado']);
        EstadoPedido::create(['nombre' => 'Anulado']);
        /*
        $lineasFile = database_path('seeders/files/data_lineas.xlsx');

        if (file_exists($lineasFile)) {
            Excel::import(new LineasDesdeExcelImport, $lineasFile);
            $this->command->info('Pedidos y líneas importadas correctamente.');
        } else {
            $this->command->warn('Archivo de líneas no encontrado.');
        }
            */
            
    }
}
