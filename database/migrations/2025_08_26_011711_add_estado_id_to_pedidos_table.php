<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('estado_pedido_id')
                  ->nullable()
                  ->constrained('estado_pedidos') // ⚠️ asegúrate que la tabla se llame "estados"
                  ->after('fecha_entrega');
        });
    }

    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('estado_pedido_id');
        });
    }
};
