<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comisions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gestora_id')
                ->constrained('gestoras')
                ->cascadeOnDelete();

            $table->foreignId('servicio_id')
                ->constrained('servicios')
                ->restrictOnDelete();

            $table->decimal('precio_base', 10, 2);
            $table->decimal('porcentaje_comision', 5, 2);
            $table->decimal('importe_comision', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comisions');
    }
};
