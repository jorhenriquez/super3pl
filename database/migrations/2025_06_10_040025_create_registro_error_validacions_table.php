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
            Schema::create('registro_error_validacions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('pedido_id')->nullable()->constrained()->onDelete('set null');
                $table->string('codigo_ingresado')->nullable();
                $table->string('mensaje_error');
                $table->unsignedTinyInteger('error_type_id'); // 1, 2, 3...
                $table->timestamps();
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_error_validacions');
    }
};
