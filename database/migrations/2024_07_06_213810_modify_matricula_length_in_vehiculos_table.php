<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMatriculaLengthInVehiculosTable extends Migration
{
    public function up()
    {
        // Primero eliminamos el índice único existente
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropUnique('vehiculos_matricula_unique'); // Nombre del índice existente
        });

        // Luego modificamos la longitud del campo matricula
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->string('matricula', 20)->change();
        });

        // Finalmente, agregamos un nuevo índice único con la longitud modificada
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->unique('matricula');
        });
    }

    public function down()
    {
        // Si necesitas revertir la migración, puedes hacerlo de esta manera
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropUnique('vehiculos_matricula_unique');
            $table->string('matricula', 16)->change();
            $table->unique('matricula');
        });
    }
}
