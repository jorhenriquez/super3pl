<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('lineas_pedidos', function (Blueprint $table) {
            $table->integer('cantidad_asignada_manual')->default(0);
        });
    }

    public function down()
    {
        Schema::table('lineas_pedidos', function (Blueprint $table) {
            $table->dropColumn('cantidad_asignada_manual');
        });
    }

};
