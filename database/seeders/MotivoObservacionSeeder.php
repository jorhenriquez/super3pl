<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MotivoObservacion;


class MotivoObservacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $motivos = [
            'Caja faltante',
            'No ingresa código',
            'Error en etiqueta',
            'Caja cambiada',
        ];

        foreach ($motivos as $motivo) {
            MotivoObservacion::create(['nombre' => $motivo]);
        }
    }

}
