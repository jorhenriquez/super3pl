<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// migration: create_linea_ingresos_table
    public function up()
    {
        Schema::create('linea_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingreso_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad_total');
            $table->integer('cantidad_valida')->default(0);
            $table->integer('cantidad_pedido')->default(0);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linea_ingresos');
    }
};
