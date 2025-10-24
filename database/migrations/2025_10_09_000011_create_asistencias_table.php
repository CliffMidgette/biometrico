<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_dispositivo')->nullable();
            $table->date('fecha');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->enum('estado_asistencia', ['presente','tarde','ausente','licencia'])->default('presente');
            $table->enum('metodo_registro', ['biometrico','manual'])->default('biometrico');
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreign('id_dispositivo')->references('id_dispositivo')->on('dispositivos_biometricos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencias');
    }
};