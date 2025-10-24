<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('personal_administrativo', function (Blueprint $table) {
            $table->id('id_personal');
            $table->unsignedBigInteger('id_usuario');
            $table->enum('cargo', ['Director', 'Secretaria']);
            $table->string('departamento', 50)->default('Administración');
            $table->time('horario_inicio')->nullable();
            $table->time('horario_fin')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_administrativo');
    }
};