<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_states', function (Blueprint $table) {
            // Borramos la columna vieja (no hay datos importantes aún)
            $table->dropColumn('tipo_aprobacion');
        });

        Schema::table('academic_states', function (Blueprint $table) {
            // La volvemos a crear como enum con los valores que usa tu formulario
            $table->enum('tipo_aprobacion', ['directa', 'final'])
                  ->nullable()
                  ->after('anio_regularizacion');
        });
    }

    public function down(): void
    {
        Schema::table('academic_states', function (Blueprint $table) {
            $table->dropColumn('tipo_aprobacion');

            // Si querés, recreás acá la definición anterior,
            // o simplemente la dejás como string genérico:
            $table->string('tipo_aprobacion', 50)->nullable();
        });
    }
};
