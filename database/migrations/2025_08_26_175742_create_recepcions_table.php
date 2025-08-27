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
        Schema::create('recepcions', function (Blueprint $table) {
            $table->id();
            $table->integer('numeroActa')->nullable(); // N° acta de recepción
            $table->string('referencia')->nullable(); // N° documento o guía
            $table->string('referencia2')->nullable(); // proveedor o cliente
			$table->date('fechaAlta')->nullable();
            $table->date('fechaConfirmacion')->nullable();
            $table->date('fechaUbicacion')->nullable();
            $table->date('fechaBaja')->nullable();
            $table->date('clienteOrigen')->nullable();
            $table->string('centroOrigen')->nullable();
            $table->string('delegacion')->nullable();
            $table->string('deposito')->nullable();
            $table->string('almacen')->nullable();
            $table->string('claveMovimiento')->nullable();
            $table->string('consignatario')->nullable();
            $table->string('observaciones')->nullable();
            $table->date('fechaLlegada')->nullable();
            $table->string('matricula')->nullable();
            $table->string('conductor')->nullable();
            $table->string('nifConductor')->nullable();
            $table->decimal('temperatura',6,2)->nullable();
            $table->integer('bultos')->nullable();
            $table->decimal('volumen', 12, 3)->nullable();
            $table->decimal('peso', 12, 3)->nullable();
            $table->string('proveedor')->nullable();
            $table->string('ordenCompra')->nullable();
            $table->string('asn')->nullable();
            $table->string('centroCoste')->nullable();
            $table->foreignId('estado_recepcion_id')->nullable()->constrained('estado_recepcions'); // Ej: en revisión, completada
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepcions');
    }
};
