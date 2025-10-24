<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('usuarios')->insertOrIgnore([
            [
                'nombres' => 'Admin',
                'apellidos' => 'Sistema',
                'cedula' => '00000000',
                'email' => 'admin@local.test',
                'telefono' => '',
                'id_rol' => 1,
                'password' => Hash::make('changeme123'),
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}