@extends('layouts.app')

@section('title', 'Panel Moderador — FLUXXO')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#7C3AED]/10 text-[#7C3AED] mb-2">
                    Supervisión Operativa (RBAC: Moderador)
                </span>
                <h1 class="text-2xl font-bold text-[#111827] tracking-tight">Centro de Monitoreo de Colas</h1>
                <p class="text-sm text-gray-600 mt-0.5">Resumen de sedes, horarios activos, reglas de prioridad y estado de turnos.</p>
            </div>
            <div class="text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded-lg px-3.5 py-2 shadow-sm">
                Horarios activos configurados: <span class="font-bold text-[#111827]">{{ $totalHorariosActivos }}</span>
            </div>
        </div>

        {{-- Resumen de Estados de Turnos --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">En Espera</p>
                <p class="mt-2 text-3xl font-bold text-amber-600">{{ $resumenEstados['en_espera'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Llamados</p>
                <p class="mt-2 text-3xl font-bold text-[#7C3AED]">{{ $resumenEstados['llamado'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">En Atención</p>
                <p class="mt-2 text-3xl font-bold text-[#10B981]">{{ $resumenEstados['en_atencion'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Finalizados</p>
                <p class="mt-2 text-3xl font-bold text-[#111827]">{{ $resumenEstados['finalizado'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Sedes --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-base font-semibold text-[#111827] mb-4">Sedes Operativas</h2>
                <div class="divide-y divide-gray-100">
                    @forelse ($sedes as $sede)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-[#111827]">
                                    {{ $sede->nombre }}
                                    <span class="text-xs font-normal text-gray-500">({{ $sede->codigo }})</span>
                                </p>
                                <p class="text-xs text-gray-500">{{ $sede->ubicacion }}</p>
                            </div>
                            <div class="text-right text-xs text-gray-600 shrink-0">
                                <span class="font-semibold text-[#111827]">{{ $sede->taquillas_count }}</span> taquillas &middot;
                                <span class="font-semibold text-[#111827]">{{ $sede->servicios_count }}</span> servicios
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No hay sedes registradas.</p>
                    @endforelse
                </div>
            </div>

            {{-- Reglas de Prioridad --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-base font-semibold text-[#111827] mb-4">Reglas de Prioridad</h2>
                <div class="divide-y divide-gray-100">
                    @forelse ($reglasPrioridad as $regla)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-[#111827]">{{ $regla->nombre }}</p>
                                <p class="text-xs text-gray-500">{{ $regla->criterio }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#7C3AED]/10 text-[#7C3AED]">
                                    Nivel {{ $regla->nivel }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-[#111827]">
                                    Peso {{ $regla->peso_prioridad }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No hay reglas de prioridad configuradas.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Monitoreo de Turnos --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-base font-semibold text-[#111827]">Monitoreo de Turnos Recientes</h2>
                <span class="text-xs text-gray-500">Últimos {{ $turnosRecientes->count() }} registros</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left">Turno</th>
                            <th class="px-6 py-3 text-left">Cliente</th>
                            <th class="px-6 py-3 text-left">Servicio</th>
                            <th class="px-6 py-3 text-left">Taquilla</th>
                            <th class="px-6 py-3 text-left">Prioridad</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($turnosRecientes as $turno)
                            <tr>
                                <td class="px-6 py-3 font-bold text-[#111827]">{{ $turno->codigo_turno }}</td>
                                <td class="px-6 py-3 text-gray-700">{{ $turno->cliente?->nombre ?? '—' }}</td>
                                <td class="px-6 py-3 text-gray-700">{{ $turno->servicio?->nombre ?? '—' }}</td>
                                <td class="px-6 py-3 text-gray-700">{{ $turno->taquilla?->codigo ?? 'Sin asignar' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $turno->reglaPrioridad?->nombre ?? 'General' }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-[#111827] capitalize">
                                        {{ str_replace('_', ' ', $turno->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">No hay turnos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
