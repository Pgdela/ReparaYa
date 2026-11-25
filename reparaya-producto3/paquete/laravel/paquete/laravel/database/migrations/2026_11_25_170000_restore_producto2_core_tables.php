<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefono', 30)->nullable()->after('role');
        });

        Schema::create('tecnicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nombre_completo', 100);
            $table->foreignId('servicio_id')->nullable()->constrained('servicios')->nullOnDelete();
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });

        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('localizador', 20)->unique();
            $table->foreignId('cliente_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('tecnico_id')->nullable()->constrained('tecnicos')->nullOnDelete();
            $table->foreignId('servicio_id')->constrained('servicios')->restrictOnDelete();
            $table->text('descripcion');
            $table->string('direccion');
            $table->string('telefono_contacto', 30);
            $table->dateTime('fecha_servicio');
            $table->string('franja_horaria', 20)->default('Mañana');
            $table->string('tipo_urgencia', 20)->default('Estándar');
            $table->string('estado', 30)->default('Pendiente');
            $table->decimal('precio_base', 10, 2);
            $table->timestamps();
        });

        Schema::create('historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidencia_id')->constrained('incidencias')->cascadeOnDelete();
            $table->string('estado_anterior', 30)->nullable();
            $table->string('estado_nuevo', 30);
            $table->foreignId('usuario_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('fecha_cambio');
            $table->string('observacion', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estados');
        Schema::dropIfExists('incidencias');
        Schema::dropIfExists('tecnicos');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('telefono');
        });
    }
};
