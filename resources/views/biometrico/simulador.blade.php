<!-- filepath: resources/views/biometrico/simulador.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Biométrico - Marcaje</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Simulador Biométrico -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-4 border-gray-300">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 text-center">
                    <h1 class="text-2xl font-bold text-white mb-2">Sistema Biométrico</h1>
                    <p class="text-green-100 text-sm">Coloque su dedo en el lector</p>
                    <div class="mt-4 text-green-100 text-lg font-mono">
                        {{ date('H:i:s') }} | {{ date('d/m/Y') }}
                    </div>
                </div>

                <!-- Mensajes -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                        <div class="font-semibold">✅ Éxito</div>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                        <div class="font-semibold">❌ Error</div>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                <!-- Simulador de Huella -->
                <div class="p-8 text-center">
                    <div class="mb-6">
                        <div class="mx-auto w-32 h-32 bg-gray-800 rounded-full flex items-center justify-center mb-4">
                            <div class="w-24 h-24 bg-gray-600 rounded-full flex items-center justify-center">
                                <div class="text-gray-300 text-4xl">👆</div>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">Simulador de Lector Biométrico</p>
                        <p class="text-xs text-gray-500 mt-1">Horario: 6:00 AM - 8:15 AM</p>
                    </div>

                    <!-- Formulario de Simulación -->
                    <form method="POST" action="{{ route('biometrico.marcar') }}">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ingrese su Cédula para Simular
                            </label>
                            <input type="text" 
                                   name="cedula" 
                                   required 
                                   pattern="[0-9]{8,10}"
                                   placeholder="Ej: 12345678"
                                   maxlength="10"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-center text-lg font-mono focus:outline-none focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Solo números, sin puntos ni espacios</p>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                            Marcar Asistencia
                        </button>
                    </form>

                    <!-- Enlace al sistema administrativo -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('login') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Acceso Administrativo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh cada minuto para actualizar la hora
        setInterval(function() {
            location.reload();
        }, 60000);
    </script>
</body>
</html>