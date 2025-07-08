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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('peso', 10, 4)->nullable()->after('codigo'); // o el campo que prefieras
            $table->decimal('volumen', 8, 4)->nullable()->after('peso');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['peso', 'volumen']);
        });
    }

};
