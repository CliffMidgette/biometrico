<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('roles')->insertOrIgnore([
            ['id_rol'=>1,'nombre_rol'=>'Administrador'],
            ['id_rol'=>2,'nombre_rol'=>'Secretaria'],
            ['id_rol'=>3,'nombre_rol'=>'Profesor'],
            ['id_rol'=>4,'nombre_rol'=>'Portero'],
            ['id_rol'=>5,'nombre_rol'=>'Personal_Limpieza'],
            ['id_rol'=>6,'nombre_rol'=>'Alumno'],
        ]);

        DB::table('configuraciones')->insertOrIgnore([
            ['clave'=>'nombre_colegio','valor'=>'Unidad Educativa Holanda','created_at'=>$now,'updated_at'=>$now],
            ['clave'=>'horario_entrada','valor'=>'07:30:00','created_at'=>$now,'updated_at'=>$now],
            ['clave'=>'horario_salida','valor'=>'12:30:00','created_at'=>$now,'updated_at'=>$now],
            ['clave'=>'tolerancia_minutos','valor'=>'15','created_at'=>$now,'updated_at'=>$now],
        ]);

        DB::table('cursos')->insertOrIgnore([
            ['id_curso'=>1,'grado'=>'1ro Primaria','seccion'=>'A','turno'=>'Mañana','created_at'=>$now,'updated_at'=>$now],
            ['id_curso'=>2,'grado'=>'2do Primaria','seccion'=>'A','turno'=>'Mañana','created_at'=>$now,'updated_at'=>$now],
        ]);

        DB::table('materias')->insertOrIgnore([
            ['id_materia'=>1,'nombre'=>'Matemáticas','area'=>'Ciencias','created_at'=>$now,'updated_at'=>$now],
            ['id_materia'=>2,'nombre'=>'Lenguaje','area'=>'Comunicación','created_at'=>$now,'updated_at'=>$now],
        ]);
    }
}
