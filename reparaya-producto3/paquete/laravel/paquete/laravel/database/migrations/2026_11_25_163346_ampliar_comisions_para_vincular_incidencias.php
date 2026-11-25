<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comisions', function (Blueprint $table) {
            $table->foreignId('incidencia_id')
                ->nullable()
                ->after('gestora_id')
                ->constrained('incidencias')
                ->nullOnDelete();

            $table->dateTime('fecha_generacion')
                ->nullable()
                ->after('importe_comision');
        });
    }

    public function down(): void
    {
        Schema::table('comisions', function (Blueprint $table) {
            $table->dropForeign(['incidencia_id']);
            $table->dropColumn(['incidencia_id', 'fecha_generacion']);
        });
    }
};
