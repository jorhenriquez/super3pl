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
        Schema::create('recepcion_lineas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recepcion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('cantidad');          // cantidad ingresada
            $table->string('paletizacion')->nullable(); // información de paletización
            $table->decimal('peso', 12, 3)->nullable(); // opcional, si se registra el
            $table->decimal('volumen', 12, 3)->nullable(); // opcional, si se registra el
            $table->string('tipoMercancia')->nullable(); // opcional, tipo de
            $table->string('tipoUnidad')->nullable(); // opcional, tipo de unidad (caja, palet, etc.)
            $table->string('lote')->nullable(); // opcional, lote del producto
            $table->date('fechaCaducidad')->nullable(); // opcional, fecha de caducidad
            $table->string('ssccCliente')->nullable(); // opcional, SSCC del cliente
            $table->text('observaciones')->nullable(); // cualquier observación adicional
            $table->decimal('paletAlto',6,3)->nullable(); // opcional, altura del palet
            $table->string('tipoEmbalaje')->nullable(); // opcional, tipo de embalaje
            $table->string('itemOrdenCompra')->nullable(); // opcional, ítem de la orden de compra
            $table->string('posicionAsn')->nullable();
            $table->string('centroCoste')->nullable(); // opcional, centro de coste asociado
            $table->string('idBulto')->nullable(); // opcional, identificador del  
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepcion_lineas');
    }
};
