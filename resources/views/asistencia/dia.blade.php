@php
use Illuminate\Support\Facades\Auth;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asistencias del Día') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">📊 Asistencias del Día</h1>
                        <div class="text-sm text-gray-600">
                            {{ isset($fecha) ? \Carbon\Carbon::parse($fecha)->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y') }}
                        </div>
                    </div>

                    <!-- Selector de Fecha -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="font-semibold text-blue-800 mb-3">📅 Seleccionar Fecha</h4>
                        <form method="GET" action="#" id="form-fecha">
                            <div class="flex gap-3 items-center">
                                <label class="text-blue-700 font-medium">Fecha:</label>
                                <input type="date" 
                                       id="fecha-selector" 
                                       class="px-3 py-2 border rounded-lg"
                                       value="{{ request('fecha', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                       max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                       min="{{ \Carbon\Carbon::now()->subDays(30)->format('Y-m-d') }}">
                                <button type="button" 
                                        onclick="verFecha()" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    🔍 Ver Asistencias
                                </button>
                                <a href="{{ route('asistencia.dia') }}" 
                                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                                    📅 Hoy
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Script para manejar el selector -->
                    <script>
                    function verFecha() {
                        const fecha = document.getElementById('fecha-selector').value;
                        if (fecha) {
                            window.location.href = "{{ url('/asistencia/fecha') }}/" + fecha;
                        }
                    }
                    </script>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hora
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombres y Apellidos
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cédula
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rol
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Método
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Curso
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($asistencias as $asistencia)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                        {{ \Carbon\Carbon::parse($asistencia->hora_entrada)->format('H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $asistencia->nombres }} {{ $asistencia->apellidos }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $asistencia->cedula }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $asistencia->nombre_rol }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($asistencia->estado_asistencia === 'presente')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                ✅ Presente
                                            </span>
                                        @elseif($asistencia->estado_asistencia === 'tarde')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                ⏰ Tarde
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($asistencia->metodo_registro === 'biometrico')
                                            👆 Biométrico
                                        @else
                                            ✏️ Manual
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($asistencia->grado && $asistencia->seccion)
                                            {{ $asistencia->grado }} {{ $asistencia->seccion }}
                                        @else
                                            Personal
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No hay registros de asistencia para hoy
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('biometrico.publico') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            🔙 Volver al Biométrico
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
