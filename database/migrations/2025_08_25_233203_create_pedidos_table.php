<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_pedido');  // Nro único: REFERENCIA en ALERCE
            $table->integer('albaran')->nullable();      // Albarán de envío, es una referencia que entrega WMS
            $table->enum('origen', ['cliente', 'wms']); // de dónde proviene
            $table->date('fecha_entrega')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
