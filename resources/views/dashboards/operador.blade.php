@extends('layouts.app')

@section('title', 'Puesto de Atención Operador — FLUXXO')

@section('content')
    <div class="space-y-6">
        {{-- Encabezado de Taquilla Asignada --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#7C3AED]/10 text-[#7C3AED] mb-2">
                    Puesto de Atención (RBAC: Operador)
                </span>
                @if ($taquilla)
                    <h1 class="text-2xl font-bold text-[#111827] tracking-tight">
                        {{ $taquilla->nombre }} ({{ $taquilla->codigo }})
                    </h1>
                    <p class="text-sm text-gray-600 mt-0.5">
                        Sede: <span class="font-medium text-[#111827]">{{ $taquilla->dependencia?->nombre ?? '—' }}</span>
                        &middot; Estado de taquilla:
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#10B981]/10 text-[#10B981] capitalize">
                            {{ $taquilla->estado }}
                        </span>
                    </p>
                @else
                    <h1 class="text-2xl font-bold text-[#111827] tracking-tight">Sin taquilla asignada</h1>
                    <p class="text-sm text-gray-600 mt-0.5">Solicita al administrador que te asigne un módulo de atención.</p>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    onclick="alert('Acción Llamar Siguiente Turno lista para conectar en el siguiente módulo.')"
                    class="inline-flex items-center justify-center rounded-lg bg-[#10B981] hover:bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors"
                >
                    Llamar Siguiente Turno
                </button>
                <button
                    type="button"
                    onclick="alert('Acción Pausar Taquilla lista para conectar en el siguiente módulo.')"
                    class="inline-flex items-center justify-center rounded-lg bg-white hover:bg-gray-50 border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors"
                >
                    Pausar Taquilla
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Turno En Atención Actual --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-base font-semibold text-[#111827] mb-4">Turno en Atención Actual</h2>

                    @if ($turnoEnAtencion)
                        <div class="rounded-xl border border-violet-200 bg-violet-50/50 p-5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-[#7C3AED]">En curso</span>
                                <span class="text-xs font-medium text-gray-500">
                                    Desde {{ $turnoEnAtencion->fecha_inicio_atencion?->format('H:i') ?? '—' }}
                                </span>
                            </div>
                            <p class="text-4xl font-bold text-[#111827] mt-2">{{ $turnoEnAtencion->codigo_turno }}</p>
                            <p class="text-sm font-semibold text-[#111827] mt-2">{{ $turnoEnAtencion->cliente?->nombre }}</p>
                            <p class="text-xs text-gray-600">CC {{ $turnoEnAtencion->cliente?->cedula }}</p>
                            <div class="mt-3 pt-3 border-t border-violet-200/70 text-xs text-gray-700 space-y-1">
                                <p><strong>Servicio:</strong> {{ $turnoEnAtencion->servicio?->nombre }}</p>
                                <p><strong>Prioridad:</strong> {{ $turnoEnAtencion->reglaPrioridad?->nombre ?? 'General' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center text-sm text-gray-500">
                            No hay ningún turno en atención en este momento.
                        </div>
                    @endif
                </div>

                <div class="mt-5 grid grid-cols-2 gap-2.5">
                    <button
                        type="button"
                        onclick="alert('Acción Iniciar Atención lista para conectar en el siguiente módulo.')"
                        class="rounded-lg bg-[#7C3AED] hover:bg-violet-700 px-3 py-2 text-xs font-semibold text-white transition-colors"
                    >
                        Iniciar Atención
                    </button>
                    <button
                        type="button"
                        onclick="alert('Acción Finalizar Turno lista para conectar en el siguiente módulo.')"
                        class="rounded-lg bg-[#10B981] hover:bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition-colors"
                    >
                        Finalizar Turno
                    </button>
                    <button
                        type="button"
                        onclick="alert('Acción Marcar Ausente lista para conectar en el siguiente módulo.')"
                        class="col-span-2 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-2 text-xs font-semibold text-red-700 transition-colors"
                    >
                        Marcar Cliente Ausente
                    </button>
                </div>
            </div>

            {{-- Lista de Turnos en Espera y Llamados --}}
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-[#111827]">Cola de Turnos (En Espera y Llamados)</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-[#111827]">
                        {{ $turnosCola->count() }} en cola
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-5 py-3 text-left">Turno</th>
                                <th class="px-5 py-3 text-left">Cliente</th>
                                <th class="px-5 py-3 text-left">Servicio</th>
                                <th class="px-5 py-3 text-left">Prioridad</th>
                                <th class="px-5 py-3 text-left">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($turnosCola as $turno)
                                <tr>
                                    <td class="px-5 py-3.5 font-bold text-[#111827]">{{ $turno->codigo_turno }}</td>
                                    <td class="px-5 py-3.5">
                                        <p class="font-medium text-[#111827]">{{ $turno->cliente?->nombre ?? '—' }}</p>
                                        <p class="text-xs text-gray-500">CC {{ $turno->cliente?->cedula ?? '—' }}</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-700">
                                        {{ $turno->servicio?->nombre ?? '—' }}
                                        @if ($turno->servicio?->dependencia)
                                            <span class="block text-xs text-gray-400">{{ $turno->servicio->dependencia->nombre }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ $turno->reglaPrioridad?->nombre ?? 'General' }} ({{ $turno->puntaje_prioridad }})
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if ($turno->estado === 'llamado')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#7C3AED]/10 text-[#7C3AED]">
                                                Llamado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                                En espera
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                                        No hay turnos en espera ni llamados actualmente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
