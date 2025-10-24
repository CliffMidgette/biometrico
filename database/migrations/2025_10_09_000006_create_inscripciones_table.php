<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id('id_inscripcion');
            $table->unsignedBigInteger('id_estudiante');
            $table->unsignedBigInteger('id_curso');
            $table->date('fecha_inscripcion');
            $table->enum('estado', ['activo', 'retirado'])->default('activo');
            $table->timestamps();

            $table->foreign('id_estudiante')->references('id_usuario')->on('usuarios');
            $table->foreign('id_curso')->references('id_curso')->on('cursos');
            $table->unique(['id_estudiante', 'id_curso'], 'unique_student_course');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscripciones');
    }
};