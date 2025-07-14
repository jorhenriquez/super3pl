<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMotivoObservacionIdToObservacionLineaPedidosTable extends Migration
{
    public function up()
    {
        Schema::table('lineas_pedidos', function (Blueprint $table) {
            $table->foreignId('motivo_observacion_id')
                  ->nullable()
                  ->constrained('motivo_observacions')
                  ->onDelete('set null')
                  ->after('descripcion');
        });
    }

    public function down()
    {
        Schema::table('lineas_pedidos', function (Blueprint $table) {
            $table->dropForeign(['motivo_observacion_id']);
            $table->dropColumn('motivo_observacion_id');
        });
    }
}
