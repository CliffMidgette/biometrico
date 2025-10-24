<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // ← AGREGAR ESTA LÍNEA
use Illuminate\Support\Facades\Auth; // ← AGREGAR PARA AUDITORÍA

class AsistenciaController extends Controller
{
    /**
     * Mostrar el simulador biométrico (pantalla principal de marcado)
     */
    public function simuladorBiometrico()
    {
        return view('asistencia.simulador');
    }

    /**
     * Registrar asistencia (simulando lectura biométrica)
     */
    public function registrarAsistencia(Request $request)
    {
        // DEBUG: Ver qué datos llegan
        Log::info('Datos recibidos:', $request->all());
        
        $request->validate([
            'cedula' => 'required|string|min:7|max:8|exists:usuarios,cedula'
        ]);

        $usuario = Usuario::where('cedula', $request->cedula)->first();
        
        // DEBUG: Ver si encontró el usuario
        Log::info('Usuario encontrado:', $usuario ? $usuario->toArray() : ['ERROR' => 'Usuario no encontrado']);
        
        if (!$usuario) {
            return back()->with('error', 'Usuario no encontrado con cédula: ' . $request->cedula);
        }

        $hoy = Carbon::now()->format('Y-m-d');
        $horaActual = Carbon::now()->format('H:i:s');

        // DEBUG: Ver fechas
        Log::info('Fecha y hora:', ['fecha' => $hoy, 'hora' => $horaActual]);

        // Verificar si ya registró asistencia hoy
        $asistenciaExistente = DB::table('asistencias')
            ->where('id_usuario', $usuario->id_usuario)
            ->where('fecha', $hoy)
            ->first();

        if ($asistenciaExistente) {
            return back()->with('error', $usuario->nombres . ' ' . $usuario->apellidos . ' ya registró asistencia hoy.');
        }

        // Obtener horario de entrada desde configuraciones
        $horarioEntrada = DB::table('configuraciones')
            ->where('clave', 'horario_entrada')
            ->value('valor');

        $tolerancia = DB::table('configuraciones')
            ->where('clave', 'tolerancia_minutos')
            ->value('valor');

        // DEBUG: Ver configuraciones
        Log::info('Configuraciones:', ['horario' => $horarioEntrada, 'tolerancia' => $tolerancia]);

        $horaActual = Carbon::now()->format('H:i:s');
        $horaInicio = '06:00:00'; // Desde las 6 AM
        $horaFin = Carbon::createFromFormat('H:i:s', $horarioEntrada)
            ->addMinutes((int)$tolerancia)
            ->format('H:i:s'); // Hasta 8:15 AM

        // RECHAZAR si está fuera del horario permitido
        if ($horaActual < $horaInicio || $horaActual > $horaFin) {
            return back()->with('error', 
                'Horario de registro expirado. Solo se permite de ' . 
                $horaInicio . ' a ' . $horaFin . '. Hora actual: ' . $horaActual
            );
        }

        // Determinar estado de asistencia (SOLO si está en horario permitido)
        $estadoAsistencia = $horaActual <= $horarioEntrada ? 'presente' : 'tarde';

        // DEBUG: Ver estado calculado
        Log::info('Estado calculado:', ['limite' => $horaFin, 'actual' => $horaActual, 'estado' => $estadoAsistencia]);

        try {
            // Registrar asistencia
            $resultado = DB::table('asistencias')->insert([
                'id_usuario' => $usuario->id_usuario,
                'fecha' => $hoy,
                'hora_entrada' => $horaActual,
                'estado_asistencia' => $estadoAsistencia,
                'metodo_registro' => 'biometrico',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            // Obtener el ID del registro recién creado
            $asistenciaId = DB::getPdo()->lastInsertId();

            // Crear log de auditoría para registro biométrico
            DB::table('logs_asistencia')->insert([
                'id_asistencia' => $asistenciaId,
                'accion' => 'crear',
                'usuario_id' => $usuario->id_usuario, // En biométrico, el usuario se registra a sí mismo
                'datos_anteriores' => null,
                'datos_nuevos' => json_encode([
                    'id_usuario' => $usuario->id_usuario,
                    'fecha' => $hoy,
                    'hora_entrada' => $horaActual,
                    'estado_asistencia' => $estadoAsistencia,
                    'metodo_registro' => 'biometrico'
                ]),
                'ip_address' => $request->ip(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            // DEBUG: Ver si se insertó
            Log::info('Inserción exitosa:', ['resultado' => $resultado]);

            $mensaje = $estadoAsistencia === 'presente' 
                ? '¡Asistencia registrada correctamente!' 
                : '¡Llegada tardía registrada!';

            // Solo permitir salida para personal (roles 1, 2: admin, secretaria)
            if (in_array($usuario->id_rol, [1, 2])) {
                // El personal puede registrar salida más tarde
                // Por ahora solo registramos entrada
            }

            return back()->with('success', $usuario->nombres . ' ' . $usuario->apellidos . ' - ' . $mensaje);
            
        } catch (\Exception $e) {
            Log::error('Error al insertar:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al registrar asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Ver asistencias del día (para administrador/secretaria)
     */
    public function asistenciasDelDia()
    {
        $hoy = Carbon::now()->format('Y-m-d');
        
        $asistencias = DB::table('asistencias as a')
            ->join('usuarios as u', 'a.id_usuario', '=', 'u.id_usuario')
            ->join('roles as r', 'u.id_rol', '=', 'r.id_rol')
            ->leftJoin('estudiantes as e', 'u.id_usuario', '=', 'e.id_usuario')
            ->leftJoin('cursos as c', 'e.id_curso', '=', 'c.id_curso')
            ->where('a.fecha', $hoy)
            ->select('u.nombres', 'u.apellidos', 'u.cedula', 'r.nombre_rol', 
                    'a.hora_entrada', 'a.estado_asistencia', 'a.metodo_registro',
                    'c.grado', 'c.seccion') // ← AGREGAR CURSO
            ->orderBy('a.hora_entrada')
            ->get();

        return view('asistencia.dia', compact('asistencias'));
    }

    /**
     * Asistencias por fecha (para administrador/secretaria)
     */
    public function asistenciasPorFecha($fecha)
    {
        $asistencias = DB::table('asistencias as a')
            ->join('usuarios as u', 'a.id_usuario', '=', 'u.id_usuario')
            ->join('roles as r', 'u.id_rol', '=', 'r.id_rol')
            ->leftJoin('estudiantes as e', 'u.id_usuario', '=', 'e.id_usuario')
            ->leftJoin('cursos as c', 'e.id_curso', '=', 'c.id_curso')
            ->where('a.fecha', $fecha)
            ->select('u.nombres', 'u.apellidos', 'u.cedula', 'r.nombre_rol', 
                    'a.hora_entrada', 'a.estado_asistencia', 'a.metodo_registro',
                    'c.grado', 'c.seccion')
            ->orderBy('a.hora_entrada')
            ->get();

        return view('asistencia.dia', compact('asistencias', 'fecha'));
    }

    /**
     * Registro manual de asistencia (solo admin/secretaria)
     */
    public function registroManual()
    {
        $usuarios = Usuario::orderBy('nombres')->get(); // Quitar ->with(['rol'])
        return view('asistencia.manual', compact('usuarios'));
    }

    /**
     * Guardar registro manual
     */
    public function guardarRegistroManual(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'fecha' => 'required|date|before_or_equal:today|after_or_equal:' . Carbon::now()->subDays(7)->format('Y-m-d'), // Solo últimos 7 días
            'hora_entrada' => 'required_unless:estado_asistencia,licencia,ausente',
            'estado_asistencia' => 'required|in:presente,tarde,ausente,licencia',
            'observaciones' => 'required|min:10'
        ]);

        // AUTO-CALCULAR estado basándose en la hora (a menos que sea licencia)
        if ($request->estado_asistencia != 'licencia' && $request->estado_asistencia != 'ausente') {
            $horarioEntrada = DB::table('configuraciones')
                ->where('clave', 'horario_entrada')
                ->value('valor');
            
            // Auto-calcular: si es después de 8:00 AM = tarde
            $estadoCalculado = $request->hora_entrada <= $horarioEntrada ? 'presente' : 'tarde';
            
            // Usar el estado calculado en lugar del seleccionado
            $estadoFinal = $estadoCalculado;
        } else {
            $estadoFinal = $request->estado_asistencia;
        }

        // Validar hora solo si NO es licencia ni ausente
        if (in_array($estadoFinal, ['presente', 'tarde'])) {
            // Validar que la hora esté en rango razonable (6:00 AM - 2:00 PM)
            $horaIngresada = $request->hora_entrada;
            if ($horaIngresada < '06:00:00' || $horaIngresada > '14:00:00') {
                return back()->with('error', 'Hora inválida. Debe estar entre 6:00 AM y 2:00 PM para entrada al colegio.');
            }
        }

        // Verificar si ya existe registro
        $existe = DB::table('asistencias')
            ->where('id_usuario', $request->id_usuario)
            ->where('fecha', $request->fecha)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Ya existe un registro para esta fecha.');
        }

        // Determinar hora según estado
        $horaEntrada = null;
        if (in_array($estadoFinal, ['presente', 'tarde'])) {
            $horaEntrada = $request->hora_entrada;
        }
        // Para licencia y ausente, hora queda NULL

        DB::table('asistencias')->insert([
            'id_usuario' => $request->id_usuario,
            'fecha' => $request->fecha,
            'hora_entrada' => $horaEntrada,
            'estado_asistencia' => $estadoFinal,
            'metodo_registro' => 'manual',
            'observaciones' => 'REGISTRO MANUAL: ' . $request->observaciones,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // Obtener el ID del registro recién creado
        $asistenciaId = DB::getPdo()->lastInsertId();

        // Crear log de auditoría
        DB::table('logs_asistencia')->insert([
            'id_asistencia' => $asistenciaId,
            'accion' => 'crear',
            'usuario_id' => Auth::id(),
            'datos_anteriores' => null,
            'datos_nuevos' => json_encode([
                'id_usuario' => $request->id_usuario,
                'fecha' => $request->fecha,
                'hora_entrada' => $horaEntrada,
                'estado_asistencia' => $estadoFinal,
                'metodo_registro' => 'manual',
                'observaciones' => $request->observaciones
            ]),
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return back()->with('success', 'Registro manual guardado correctamente.');
    }

    /**
     * Registrar salida (simulando lectura biométrica)
     */
    public function registrarSalida(Request $request)
    {
        $request->validate([
            'cedula' => 'required|exists:usuarios,cedula'
        ]);

        $usuario = Usuario::where('cedula', $request->cedula)->first();

        // Solo personal puede marcar salida
        if (!in_array($usuario->id_rol, [1, 2])) {
            return back()->with('error', 'Los estudiantes no deben marcar salida.');
        }

        // Buscar registro de entrada del día
        $asistencia = DB::table('asistencias')
            ->where('id_usuario', $usuario->id_usuario)
            ->where('fecha', Carbon::now()->format('Y-m-d'))
            ->whereNull('hora_salida')
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No se encontró registro de entrada para hoy.');
        }

        // Actualizar hora de salida
        DB::table('asistencias')
            ->where('id_asistencia', $asistencia->id_asistencia)
            ->update([
                'hora_salida' => Carbon::now()->format('H:i:s'),
                'updated_at' => Carbon::now()
            ]);

        return back()->with('success', 'Salida registrada: ' . $usuario->nombres . ' - ' . Carbon::now()->format('H:i:s'));
    }

    /**
     * Mostrar el simulador biométrico (pantalla principal de marcado) - Público
     */
    public function simuladorPublico()
    {
        return view('biometrico.simulador');
    }

    /**
     * Marcar asistencia (simulando lectura biométrica) - Público
     */
    public function marcarAsistencia(Request $request)
    {
        $cedula = $request->cedula;
        
        // Validar horario (6:00 AM - 8:15 AM)
        $horaActual = Carbon::now()->format('H:i:s');
        $hoy = Carbon::now()->format('Y-m-d');
        
        if ($horaActual < '06:00:00' || $horaActual > '08:15:00') {
            return back()->with('error', 'El sistema biométrico solo funciona de 6:00 AM a 8:15 AM');
        }
        
        // Buscar usuario
        $usuario = DB::table('usuarios')->where('cedula', $cedula)->first();
        
        if (!$usuario) {
            return back()->with('error', 'Cédula no encontrada en el sistema');
        }
        
        // Verificar si ya marcó hoy
        $yaMarco = DB::table('asistencias')
            ->where('id_usuario', $usuario->id_usuario)
            ->where('fecha', $hoy)
            ->exists();
        
        if ($yaMarco) {
            return back()->with('error', 'Ya has marcado asistencia hoy');
        }
        
        // Calcular estado
        $estadoAsistencia = ($horaActual <= '08:00:00') ? 'presente' : 'tarde';
        
        // Registrar asistencia
        $asistenciaId = DB::table('asistencias')->insertGetId([
            'id_usuario' => $usuario->id_usuario,
            'fecha' => $hoy,
            'hora_entrada' => $horaActual,
            'estado_asistencia' => $estadoAsistencia,
            'metodo_registro' => 'biometrico',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        // Log de auditoría
        DB::table('logs_asistencia')->insert([
            'id_asistencia' => $asistenciaId,
            'accion' => 'crear',
            'usuario_id' => $usuario->id_usuario,
            'datos_anteriores' => null,
            'datos_nuevos' => json_encode([
                'id_usuario' => $usuario->id_usuario,
                'fecha' => $hoy,
                'hora_entrada' => $horaActual,
                'estado_asistencia' => $estadoAsistencia,
                'metodo_registro' => 'biometrico'
            ]),
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        return back()->with('success', "¡Asistencia registrada! {$usuario->nombres} {$usuario->apellidos} - Estado: " . ucfirst($estadoAsistencia));
    }
}
