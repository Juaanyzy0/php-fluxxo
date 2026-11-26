@extends('layouts.app')

@section('title', 'Mi Portal de Turnos — FLUXXO')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#10B981]/10 text-[#10B981] mb-2">
                    Panel del Cliente
                </span>
                <h1 class="text-2xl font-bold text-[#111827] tracking-tight">Hola, {{ $cliente->nombre }}</h1>
                <p class="text-sm text-gray-600 mt-0.5">Consulta el estado de tu turno en tiempo real o solicita una nueva atención.</p>
            </div>

            <button
                type="button"
                onclick="alert('El módulo de solicitud de turnos se conectará en la siguiente fase.')"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#10B981] hover:bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-colors"
            >
                + Solicitar Nuevo Turno
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Perfil del Cliente --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-base font-semibold text-[#111827] mb-4">Mi Perfil</h2>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4 py-1.5 border-b border-gray-100">
                        <dt class="text-gray-500">Nombre</dt>
                        <dd class="font-medium text-[#111827] text-right">{{ $cliente->nombre }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-1.5 border-b border-gray-100">
                        <dt class="text-gray-500">Cédula</dt>
                        <dd class="font-medium text-[#111827]">{{ $cliente->cedula }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-1.5 border-b border-gray-100">
                        <dt class="text-gray-500">Teléfono</dt>
                        <dd class="font-medium text-[#111827]">{{ $cliente->telefono }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-1.5 border-b border-gray-100">
                        <dt class="text-gray-500">Estado</dt>
                        <dd>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 capitalize">
                                {{ $cliente->estado }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between items-center gap-4 pt-2">
                        <dt class="text-gray-500">Puntaje de Reputación</dt>
                        <dd class="flex items-center gap-2">
                            <span class="text-lg font-bold text-[#10B981]">{{ $cliente->puntaje_reputacion }}</span>
                            <span class="text-xs text-gray-400">/ 100</span>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Turno Activo --}}
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <h2 class="text-base font-semibold text-[#111827]">Mi Turno Activo</h2>
                        @if ($turnoActivo)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#10B981]/10 text-[#10B981] uppercase tracking-wide">
                                {{ str_replace('_', ' ', $turnoActivo->estado) }}
                            </span>
                        @endif
                    </div>

                    @if ($turnoActivo)
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Código de turno</p>
                                <p class="text-4xl font-bold text-[#111827] tracking-tight mt-1">{{ $turnoActivo->codigo_turno }}</p>
                                <p class="text-sm font-medium text-gray-700 mt-2">
                                    {{ $turnoActivo->servicio?->nombre ?? 'Servicio' }}
                                    @if ($turnoActivo->servicio?->dependencia)
                                        &middot; <span class="text-gray-500">{{ $turnoActivo->servicio->dependencia->nombre }}</span>
                                    @endif
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm bg-white border border-emerald-100 rounded-lg p-4 min-w-[210px]">
                                <div>
                                    <p class="text-xs text-gray-500">Posición en cola</p>
                                    <p class="text-lg font-bold text-[#111827]">
                                        {{ $turnoActivo->posicion_cola ? '#'.$turnoActivo->posicion_cola : '—' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Taquilla</p>
                                    <p class="text-lg font-bold text-[#111827]">
                                        {{ $turnoActivo->taquilla?->codigo ?? 'Por asignar' }}
                                    </p>
                                </div>
                                <div class="col-span-2 pt-2 border-t border-gray-100">
                                    <p class="text-xs text-gray-500">Prioridad</p>
                                    <p class="font-medium text-[#111827]">
                                        {{ $turnoActivo->reglaPrioridad?->nombre ?? 'General' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
                            <p class="text-sm font-medium text-[#111827]">No tienes ningún turno activo en este momento.</p>
                            <p class="text-xs text-gray-500 mt-1">Presiona el botón "Solicitar Nuevo Turno" cuando necesites atención.</p>
                        </div>
                    @endif
                </div>

                @if ($historialReciente->isNotEmpty())
                    <div class="mt-6 pt-5 border-t border-gray-100">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Historial Reciente</h3>
                        <div class="divide-y divide-gray-100">
                            @foreach ($historialReciente as $item)
                                <div class="py-2.5 flex items-center justify-between text-sm">
                                    <div>
                                        <span class="font-semibold text-[#111827]">{{ $item->codigo_turno }}</span>
                                        <span class="text-gray-500 ml-2">{{ $item->servicio?->nombre }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-gray-400">{{ $item->fecha_solicitud?->format('d/m/Y H:i') }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 capitalize">
                                            {{ str_replace('_', ' ', $item->estado) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
