<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('datos_biometricos', function (Blueprint $table) {
            $table->id('id_biometrico');
            $table->unsignedBigInteger('id_usuario');
            $table->enum('dedo_registrado', [
                'pulgar_derecho','indice_derecho','medio_derecho','anular_derecho','meñique_derecho',
                'pulgar_izquierdo','indice_izquierdo','medio_izquierdo','anular_izquierdo','meñique_izquierdo'
            ]);
            $table->text('template_huella');
            $table->integer('calidad_template')->default(0);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->unique(['id_usuario', 'dedo_registrado'], 'unique_user_finger');
        });
    }

    public function down()
    {
        Schema::dropIfExists('datos_biometricos');
    }
};