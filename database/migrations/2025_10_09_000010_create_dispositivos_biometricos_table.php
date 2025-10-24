<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dispositivos_biometricos', function (Blueprint $table) {
            $table->id('id_dispositivo');
            $table->string('nombre_dispositivo', 100);
            $table->string('ubicacion', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->enum('estado', ['activo','inactivo','mantenimiento'])->default('activo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dispositivos_biometricos');
    }
};