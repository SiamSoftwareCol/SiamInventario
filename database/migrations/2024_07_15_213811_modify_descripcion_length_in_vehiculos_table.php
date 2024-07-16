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
        // Modificar la tabla vehiculos
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->change();
        });

        // Modificar la tabla costos
        Schema::table('costos', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir la modificación en la tabla vehiculos
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->text('descripcion')->nullable(false)->change();
        });

        // Revertir la modificación en la tabla costos
        Schema::table('costos', function (Blueprint $table) {
            $table->text('descripcion')->nullable(false)->change();
        });
    }
};
