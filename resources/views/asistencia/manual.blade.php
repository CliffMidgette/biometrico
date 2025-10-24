@php
use Illuminate\Support\Facades\Auth;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registro Manual de Asistencia') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-xl mx-auto sm:px-4 lg:px-6">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                    <h1 class="text-xl font-bold text- mb-1">Registro Manual de Asistencia</h1>
                    <p class="text-blue-100 text-sm">Solo para casos excepcionales (fallas técnicas, heridas, etc.)</p>
                </div>

                <div class="p-6 bg-gray-50">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('asistencia.guardar-manual') }}" class="space-y-6">
                        @csrf
                        
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                                    <select name="id_usuario" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccionar usuario...</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id_usuario }}">
                                                {{ $usuario->nombres }} {{ $usuario->apellidos }} - {{ $usuario->cedula }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" required value="{{ date('Y-m-d') }}"
                                           max="{{ date('Y-m-d') }}"
                                           min="{{ date('Y-m-d', strtotime('-7 days')) }}"
                                           onchange="validarFecha()"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                
                                    <div id="error-fecha" class="text-red-500 text-sm mt-1" style="display: none;">
                                        ⚠️ La fecha no puede ser futura ni mayor a 7 días atrás.
                                    </div>
                                </div>

                                <div class="campo-hora" id="campo-hora">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Entrada</label>
                                    <input type="time" name="hora_entrada" id="hora_entrada" required value="{{ date('H:i') }}"
                                           min="06:00" max="14:00"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="text-xs text-gray-500 mt-1">Horario válido: 6:00 AM - 2:00 PM</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                    <select name="estado_asistencia" id="estado_asistencia" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="presente">Presente</option>
                                        <option value="tarde">Tarde</option>
                                        <option value="ausente">Ausente</option>
                                        <option value="licencia">Licencia</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-span-full mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del Registro Manual *</label>
                                <textarea name="observaciones" required rows="3" placeholder="Explique el motivo del registro manual (ej: falla del lector biométrico, herida en el dedo, etc.)"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Mínimo 10 caracteres</p>
                            </div>
                        </div> <!-- Cierre del formulario -->

                        <!-- Botones organizados correctamente -->
                        <div class="mt-6 flex justify-between items-center">
                            <!-- Botones de navegación a la izquierda -->
                            <div class="flex gap-2">
                                <a href="{{ route('biometrico.publico') }}" 
                                   class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                                    Biométrico
                                </a>
                                
                                <a href="{{ route('asistencia.dia') }}" 
                                   class="px-4 py-2 text-white text-sm font-medium rounded-md transition-colors"
                                   style="background-color: #059669 !important; border: 1px solid #059669;">
                                    Ver Reportes
                                </a>

                                <a href="{{ route('logs.asistencia') }}" 
                                   class="px-4 py-2 text-white text-sm font-medium rounded-md transition-colors"
                                   style="background-color: #7c3aed !important; border: 1px solid #7c3aed;">
                                    Ver Logs
                                </a>
                            </div>

                            <!-- Botón de submit a la derecha -->
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-md transition-colors">
                                Registrar Asistencia
                            </button>
                        </div>
                    </form>

                    <script>
                        // Función para calcular estado automáticamente según la hora
                        function calcularEstado() {
                            const horaEntrada = document.getElementById('hora_entrada').value;
                            const estadoSelect = document.getElementById('estado_asistencia');
                            
                            if (horaEntrada && (estadoSelect.value === 'presente' || estadoSelect.value === 'tarde')) {
                                // Horario límite: 8:00 AM
                                const horaLimite = '08:00';
                                
                                if (horaEntrada <= horaLimite) {
                                    estadoSelect.value = 'presente';
                                    estadoSelect.style.backgroundColor = '#dcfce7'; // Verde claro
                                } else {
                                    estadoSelect.value = 'tarde';
                                    estadoSelect.style.backgroundColor = '#fef3c7'; // Amarillo claro
                                }
                            }
                        }

                        // Auto-calcular cuando cambia la hora
                        document.getElementById('hora_entrada').addEventListener('change', calcularEstado);
                        document.getElementById('hora_entrada').addEventListener('input', calcularEstado);

                        // Manejar cuando se selecciona licencia/ausente
                        document.getElementById('estado_asistencia').addEventListener('change', function() {
                            const campoHora = document.getElementById('campo-hora');
                            const inputHora = document.getElementById('hora_entrada');
                            const estadoSelect = document.getElementById('estado_asistencia');
                            
                            if (this.value === 'licencia' || this.value === 'ausente') {
                                campoHora.style.display = 'none';
                                inputHora.required = false;
                                estadoSelect.style.backgroundColor = '#f3f4f6'; // Gris normal
                            } else {
                                campoHora.style.display = 'block';
                                inputHora.required = true;
                                calcularEstado(); // Recalcular cuando vuelve a presente/tarde
                            }
                        });

                        // Validar fecha en tiempo real
                        function validarFecha() {
                            const fechaInput = document.getElementById('fecha');
                            const errorDiv = document.getElementById('error-fecha');
                            const submitBtn = document.querySelector('button[type="submit"]');
                            
                            const fechaSeleccionada = new Date(fechaInput.value);
                            const hoy = new Date();
                            const hace7Dias = new Date();
                            hace7Dias.setDate(hoy.getDate() - 7);
                            
                            // Normalizar fechas (solo fecha, sin hora)
                            fechaSeleccionada.setHours(0, 0, 0, 0);
                            hoy.setHours(0, 0, 0, 0);
                            hace7Dias.setHours(0, 0, 0, 0);
                            
                            if (fechaSeleccionada > hoy) {
                                // Fecha futura
                                errorDiv.textContent = "⚠️ No se puede registrar asistencia para fechas futuras.";
                                errorDiv.style.display = "block";
                                fechaInput.style.borderColor = "#ef4444";
                                submitBtn.disabled = true;
                                submitBtn.style.opacity = "0.5";
                            } else if (fechaSeleccionada < hace7Dias) {
                                // Fecha muy antigua
                                errorDiv.textContent = "⚠️ Solo se pueden registrar fechas de los últimos 7 días.";
                                errorDiv.style.display = "block";
                                fechaInput.style.borderColor = "#ef4444";
                                submitBtn.disabled = true;
                                submitBtn.style.opacity = "0.5";
                            } else {
                                // Fecha válida
                                errorDiv.style.display = "none";
                                fechaInput.style.borderColor = "#d1d5db";
                                submitBtn.disabled = false;
                                submitBtn.style.opacity = "1";
                            }
                        }

                        // Ejecutar validación al cargar la página
                        document.addEventListener('DOMContentLoaded', function() {
                            validarFecha();
                            calcularEstado();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
