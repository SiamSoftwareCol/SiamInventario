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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('identificacion', 17)->unique();
            $table->string('motor', 17)->unique();
            $table->string('matricula', 6)->unique();
            $table->foreignId('fabricante_id')->constrained('fabricantes')->cascadeOnDelete();
            $table->foreignId('linea_id')->constrained('lineas')->cascadeOnDelete();
            $table->string('modelo', 4);
            $table->foreignId('color_id')->constrained('colors')->cascadeOnDelete();
            $table->foreignId('combustible_id')->constrained('combustibles')->cascadeOnDelete();
            $table->decimal('capacidad', 6, 2);
            $table->foreignId('trasmision_id')->constrained('trasmisions')->cascadeOnDelete();
            $table->decimal('kilometraje', 9);
            $table->text('descripcion');
            $table->string('ruta_imagen')->nullable();
            $table->date("fecha_compra")->nullable();
            $table->decimal('valor_compra', 14, 2)->nullable();
            $table->date("fecha_venta")->nullable();
            $table->decimal('valor_venta', 14, 2)->nullable();
            $table->decimal('total_costo', 14, 2)->nullable();
            $table->decimal('utilidad', 14, 2)->nullable();
            $table->foreignId('estado_id')->constrained('estados')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
