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
        Schema::table('tasks', function (Blueprint $table) {
            // Clave foranea (id categorias). Puede ser null. Null al eliminarse. Situada despues de la descripcion
            $table->foreignId('category_id')
                    ->after('description')
                    ->nullable()
                    ->constrained()
                    ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Quitar restriccion
            $table->dropForeign(['category_id']);
            //Eliminar columna
            $table->dropColumn('category_id');
        });
    }
};
