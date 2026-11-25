<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gestoras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cif')->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('direccion')->nullable();
            $table->decimal('porcentaje_comision', 5, 2)->default(0);
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gestoras');
    }
};
