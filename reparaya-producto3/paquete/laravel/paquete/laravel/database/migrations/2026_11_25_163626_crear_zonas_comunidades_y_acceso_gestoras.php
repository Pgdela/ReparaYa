<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Añadir el rol gestora a los usuarios
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('admin', 'tecnico', 'particular', 'gestora')
            NOT NULL DEFAULT 'particular'
        ");

        // Vincular cada gestora con su usuario
        Schema::table('gestoras', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        // Crear zonas
        Schema::create('zonas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        // Crear comunidades vinculadas a gestoras y zonas
        Schema::create('comunidades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gestora_id')
                ->constrained('gestoras')
                ->cascadeOnDelete();

            $table->foreignId('zona_id')
                ->constrained('zonas')
                ->restrictOnDelete();

            $table->string('nombre');
            $table->string('direccion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comunidades');
        Schema::dropIfExists('zonas');

        Schema::table('gestoras', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id']);
            $table->dropColumn('user_id');
        });

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('admin', 'tecnico', 'particular')
            NOT NULL DEFAULT 'particular'
        ");
    }
};
