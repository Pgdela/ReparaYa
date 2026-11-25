<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            $table->foreignId('comunidad_id')
                ->nullable()
                ->after('tecnico_id')
                ->constrained('comunidades')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            $table->dropForeign(['comunidad_id']);
            $table->dropColumn('comunidad_id');
        });
    }
};