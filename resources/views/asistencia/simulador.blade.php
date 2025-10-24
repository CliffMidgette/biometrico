@php
use Illuminate\Support\Facades\Auth;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sistema Biométrico') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header mejorado - TAMAÑO FIJO -->
            <div class="flex justify-center mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6" style="width: 500px;">
                    <h1 class="text-3xl font-bold text-blue-700 mb-2 text-center">🏫 UNIDAD EDUCATIVA HOLANDA</h1>
                    <h2 class="text-xl text-gray-600 mb-1 text-center">Sistema de Control Biométrico</h2>
                    <p class="text-sm text-gray-500 text-center">{{ \Carbon\Carbon::now()->format('l, d \d\e F \d\e Y') }}</p>
                </div>
            </div>

            <!-- Simulador biométrico principal - CENTRADO -->
            <div class="flex justify-center mb-8">
                <div class="bg-white rounded-xl shadow-xl overflow-hidden" style="width: 400px;">
                    
                    <!-- Mensajes de éxito/error -->
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 m-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700 font-medium">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 m-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Errores de validación -->
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 m-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">
                                        {{ $errors->first() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Simulador del dispositivo -->
                    <div class="p-6">
                        <div class="bg-gradient-to-b from-gray-800 to-gray-900 rounded-2xl p-6 shadow-2xl border border-gray-700">
                            
                            <!-- Pantalla del dispositivo -->
                            <div class="bg-black rounded-lg p-4 mb-4 border-2 border-gray-600">
                                <div class="text-center text-green-400 font-mono">
                                    <div class="text-3xl mb-2">👆</div>
                                    <div class="text-sm mb-1">LECTOR BIOMÉTRICO</div>
                                    <div class="text-xs text-green-300">COLOQUE SU DEDO</div>
                                    <div class="text-xs text-gray-400 mt-2">
                                        <div>🕐 <span id="hora-actual"></span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario -->
                            <form method="POST" action="{{ route('asistencia.registrar') }}">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" 
                                           name="cedula" 
                                           placeholder="Ingrese su cédula"
                                           class="w-full px-3 py-2 text-gray-900 text-center text-base font-mono rounded-lg border-2 border-gray-500 focus:border-blue-400 focus:outline-none bg-gray-100"
                                           required
                                           minlength="7"
                                           maxlength="8"
                                           pattern="[0-9]{7,8}"
                                           title="Solo números de 7 u 8 dígitos">
                                </div>
                                
                                <button type="submit" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-lg">
                                    🔍 VERIFICAR IDENTIDAD
                                </button>
                            </form>

                            <!-- Info del dispositivo -->
                            <div class="mt-3 text-center text-xs text-gray-400">
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>📍 Entrada Principal</div>
                                    <div>⏰ 08:00 - 08:15</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel administrativo debajo (solo para admins) - TAMAÑO FIJO -->
            @if(Auth::check() && in_array(Auth::user()->id_rol, [1, 2]))
            <div class="flex justify-center">
                <div class="bg-white rounded-xl shadow-lg p-6" style="width: 600px;">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6 text-center flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                        </svg>
                        Panel Administrativo - {{ Auth::user()->nombres }}
                    </h3>
                    
                    <div class="flex justify-center gap-4">
                        <!-- Ver asistencias -->
                        <a href="{{ route('asistencia.dia') }}" 
                           class="flex flex-col items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold p-4 rounded-lg transition duration-200 border border-blue-200 group" 
                           style="width: 160px; height: 130px;">
                            <svg class="w-8 h-8 mb-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                            </svg>
                            <span class="text-center text-sm mb-2">Ver Asistencias del Día</span>
                            <span class="text-xs text-blue-500 text-center">Registros de hoy</span>
                        </a>

                        <!-- Registro manual -->
                        <a href="{{ route('asistencia.manual') }}" 
                           class="flex flex-col items-center bg-yellow-50 hover:bg-yellow-100 text-yellow-700 font-semibold p-4 rounded-lg transition duration-200 border border-yellow-200 group"
                           style="width: 160px; height: 130px;">
                            <svg class="w-8 h-8 mb-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            <span class="text-center text-sm mb-2">Registro Manual</span>
                            <span class="text-xs text-yellow-600 text-center">Casos excepcionales</span>
                        </a>

                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" 
                           class="flex flex-col items-center bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold p-4 rounded-lg transition duration-200 border border-gray-200 group"
                           style="width: 160px; height: 130px;">
                            <svg class="w-8 h-8 mb-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                            </svg>
                            <span class="text-center text-sm mb-2">Panel Principal</span>
                            <span class="text-xs text-gray-500 text-center">Volver al dashboard</span>
                        </a>
                    </div>
                </div>
            </div>
            @else
            <!-- Información pública - TAMAÑO FIJO -->
            <div class="flex justify-center">
                <div class="bg-white rounded-xl shadow-lg p-6" style="width: 450px;">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">📋 Información del Sistema</h3>
                    <div class="flex justify-center gap-3">
                        <div class="bg-blue-50 p-3 rounded-lg text-center" style="width: 120px;">
                            <div class="text-xl font-bold text-blue-600">8:00 AM</div>
                            <div class="text-xs text-gray-600">Horario de entrada</div>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg text-center" style="width: 120px;">
                            <div class="text-xl font-bold text-green-600">15 min</div>
                            <div class="text-xs text-gray-600">Tolerancia</div>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg text-center" style="width: 120px;">
                            <div class="text-xl font-bold text-purple-600">Mañana</div>
                            <div class="text-xs text-gray-600">Turno actual</div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-700 text-center">
                            <strong>Instrucciones:</strong> Ingrese su número de cédula y presione "Verificar Identidad" para registrar su asistencia.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Registro de Salida (Solo Personal) - TAMAÑO FIJO -->
            @if(Auth::check() && in_array(Auth::user()->id_rol, [1, 2]))
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <h4 class="font-semibold text-yellow-800 mb-3">🚪 Registro de Salida (Solo Personal)</h4>
                <form method="POST" action="{{ route('asistencia.salida') }}">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text" name="cedula" placeholder="Cédula para salida" 
                               class="flex-1 px-3 py-2 border rounded-lg">
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                            🚪 Registrar Salida
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Mostrar hora actual
        function actualizarHora() {
            const ahora = new Date();
            const horaString = ahora.toLocaleTimeString('es-BO', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('hora-actual').textContent = horaString;
        }
        
        setInterval(actualizarHora, 1000);
        actualizarHora();

        // Auto-focus en el input
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.querySelector('input[name="cedula"]');
            if (input) {
                input.focus();
            }
        });
    </script>
</x-app-layout>
