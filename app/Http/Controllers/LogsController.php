<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogsController extends Controller
{
    public function index()
    {
        $logs = DB::table('logs_asistencia as l')
            ->join('asistencias as a', 'l.id_asistencia', '=', 'a.id_asistencia')
            ->join('usuarios as u_registrado', 'a.id_usuario', '=', 'u_registrado.id_usuario')
            ->join('usuarios as u_registrador', 'l.usuario_id', '=', 'u_registrador.id_usuario')
            ->select(
                'a.fecha', 
                'a.hora_entrada', 
                'a.estado_asistencia', 
                'a.metodo_registro',
                'u_registrado.nombres as usuario_nombres',
                'u_registrado.apellidos as usuario_apellidos',
                'u_registrado.cedula as usuario_cedula',
                'u_registrador.nombres as registrador_nombres',
                'u_registrador.apellidos as registrador_apellidos',
                'l.accion',
                'l.ip_address',
                'l.created_at',
                'l.datos_nuevos'
            )
            ->orderBy('l.created_at', 'desc')
            ->paginate(20);

        return view('asistencia.logs', compact('logs'));
    }
}
