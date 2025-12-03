<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->softDeletes(); // agrega columna deleted_at nullable
        });
    }

    public function down(): void
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->dropSoftDeletes(); // quita deleted_at si se hace rollback
        });
    }
};
