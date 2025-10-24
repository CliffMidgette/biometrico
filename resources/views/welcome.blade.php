<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asistencia - Colegio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-4xl w-full">
            <!-- Header Institucional -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Sistema de Asistencia</h1>
                <h2 class="text-2xl text-gray-600 mb-4">Colegio [Nombre del Colegio]</h2>
                <p class="text-gray-500">Sistema Biométrico de Control de Asistencia</p>
            </div>

            <!-- Cards principales -->
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <!-- Card Biométrico -->
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center border-2 border-green-100 hover:border-green-300 transition-colors">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Marcar Asistencia</h3>
                    <p class="text-gray-600 mb-6">Estudiantes y personal docente</p>
                    
                    <!-- Estado del sistema -->
                    @php
                        $horaActual = date('H:i:s');
                        $sistemActivo = ($horaActual >= '06:00:00' && $horaActual <= '08:15:00');
                    @endphp
                    
                    <div class="mb-6">
                        <div class="text-sm text-gray-500 mb-2">Estado del Sistema:</div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $sistemActivo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $sistemActivo ? '🟢 Activo' : '🔴 Inactivo' }}
                        </span>
                        <div class="text-xs text-gray-500 mt-2">
                            Horario: 6:00 AM - 8:15 AM
                        </div>
                        <div class="text-sm font-mono text-gray-700 mt-1">
                            Hora actual: {{ date('H:i:s') }}
                        </div>
                    </div>

                    <a href="{{ route('biometrico.publico') }}" 
                       class="inline-block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                        Acceder al Biométrico
                    </a>
                </div>

                <!-- Card Administrativo -->
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center border-2 border-blue-100 hover:border-blue-300 transition-colors">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Sistema Administrativo</h3>
                    <p class="text-gray-600 mb-6">Personal autorizado</p>
                    
                    <div class="mb-6 text-sm text-gray-600">
                        <div>• Reportes de asistencia</div>
                        <div>• Registro manual</div>
                        <div>• Auditoría del sistema</div>
                    </div>

                    <a href="{{ route('login') }}" 
                       class="inline-block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                        Iniciar Sesión
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-500 text-sm">
                <p>© {{ date('Y') }} Sistema de Asistencia Biométrico</p>
                <p class="mt-1">Desarrollado para el control eficiente de asistencia escolar</p>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh cada 30 segundos para actualizar la hora y estado
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
