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
        Schema::create('logs_asistencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_asistencia');
            $table->string('accion'); // 'crear', 'modificar', 'eliminar'
            $table->unsignedBigInteger('usuario_id'); // Quién hizo la acción
            $table->json('datos_anteriores')->nullable(); 
            $table->json('datos_nuevos');
            $table->string('ip_address');
            $table->timestamps();
            
            $table->foreign('id_asistencia')->references('id_asistencia')->on('asistencias');
            $table->foreign('usuario_id')->references('id_usuario')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_asistencia');
    }
};
