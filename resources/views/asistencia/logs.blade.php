<!-- filepath: resources/views/asistencia/logs.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Logs de Auditoría - Sistema de Asistencia
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                    <h1 class="text-xl font-bold text-white mb-1">Auditoría de Registros</h1>
                    <p class="text-purple-100 text-sm">Historial completo de acciones en el sistema</p>
                </div>

                <div class="p-6">
                    <!-- Navegación -->
                    <div class="mb-6 flex gap-3">
                        <a href="{{ route('biometrico.publico') }}" 
                           class="px-4 py-2 text-white text-sm font-medium rounded-md transition-colors"
                           style="background-color: #059669 !important; border: 1px solid #059669;">
                            Biométrico
                        </a>
                        <a href="{{ route('asistencia.manual') }}" 
                           class="px-4 py-2 text-white text-sm font-medium rounded-md transition-colors"
                           style="background-color: #2563eb !important; border: 1px solid #2563eb;">
                            Registro Manual
                        </a>
                        <a href="{{ route('asistencia.dia') }}" 
                           class="px-4 py-2 text-white text-sm font-medium rounded-md transition-colors"
                           style="background-color: #4b5563 !important; border: 1px solid #4b5563;">
                            Ver Reportes
                        </a>
                    </div>

                    <!-- Tabla de Logs -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora Log</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario Registrado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Asistencia</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrado Por</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($logs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($log->accion == 'crear') bg-green-100 text-green-800
                                            @elseif($log->accion == 'modificar') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($log->accion) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div>{{ $log->usuario_nombres }} {{ $log->usuario_apellidos }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->usuario_cedula }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div>{{ \Carbon\Carbon::parse($log->fecha)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->hora_entrada }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($log->metodo_registro == 'biometrico') bg-blue-100 text-blue-800
                                            @else bg-orange-100 text-orange-800 @endif">
                                            {{ ucfirst($log->metodo_registro) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $log->registrador_nombres }} {{ $log->registrador_apellidos }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $log->ip_address }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        No hay logs de auditoría registrados
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>