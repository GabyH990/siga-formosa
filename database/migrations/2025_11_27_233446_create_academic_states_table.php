<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('commission_id')->nullable()->constrained('commissions');
            $table->enum('estado', ['Cursando', 'Regular', 'Aprobada']);
            $table->smallInteger('anio_regularizacion')->nullable();
            $table->enum('tipo_aprobacion', ['Final', 'Aprobación directa'])->nullable();
            $table->string('libro')->nullable();
            $table->string('acta')->nullable();
            $table->string('tomo')->nullable();
            $table->tinyInteger('nota_final')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_states');
    }
};
