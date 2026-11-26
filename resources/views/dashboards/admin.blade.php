@extends('layouts.app')

@section('title', 'Panel Administrador — FLUXXO')

@section('content')
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#7C3AED]/10 text-[#7C3AED] mb-2">
                    Panel Administrativo (RBAC: Administrador)
                </span>
                <h1 class="text-2xl font-bold text-[#111827] tracking-tight">
                    {{ $organizacion?->nombre ?? 'Organización FLUXXO' }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    NIT: <span class="font-medium text-[#111827]">{{ $organizacion?->nit ?? '—' }}</span>
                    &middot; Licencia: <span class="font-medium text-[#111827]">{{ $organizacion?->licencia_codigo ?? '—' }}</span>
                    &middot; Vence: <span class="font-medium text-[#111827]">{{ $organizacion?->licencia_vencimiento?->format('d/m/Y') ?? '—' }}</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#10B981]/10 text-[#10B981] uppercase">
                    Licencia {{ $organizacion?->estado ?? 'activa' }}
                </span>
            </div>
        </div>

        {{-- Tarjetas Métricas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Sedes / Dependencias</p>
                <p class="mt-2 text-3xl font-bold text-[#111827]">{{ $metricas['sedes'] }}</p>
                <p class="mt-1 text-xs text-gray-500">Puntos físicos configurados</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Taquillas / Módulos</p>
                <p class="mt-2 text-3xl font-bold text-[#111827]">{{ $metricas['taquillas'] }}</p>
                <p class="mt-1 text-xs text-gray-500">Puestos de atención registrados</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Servicios Activos</p>
                <p class="mt-2 text-3xl font-bold text-[#111827]">{{ $metricas['servicios'] }}</p>
                <p class="mt-1 text-xs text-gray-500">Catálogo con horarios y prefijos</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Personal Staff</p>
                <p class="mt-2 text-3xl font-bold text-[#7C3AED]">{{ $metricas['staff'] }}</p>
                <p class="mt-1 text-xs text-gray-500">Administradores, moderadores y operadores</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Turnos del Día</p>
                <p class="mt-2 text-3xl font-bold text-[#10B981]">{{ $metricas['turnos_hoy'] }}</p>
                <p class="mt-1 text-xs text-gray-500">Solicitados hoy ({{ now()->format('d/m/Y') }})</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Turnos Activos en Cola</p>
                <p class="mt-2 text-3xl font-bold text-[#111827]">{{ $metricas['turnos_activos'] }}</p>
                <p class="mt-1 text-xs text-gray-500">En espera, llamados o en atención</p>
            </div>
        </div>

        {{-- Accesos Directos a Módulos de Configuración --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h2 class="text-base font-semibold text-[#111827] mb-1">Módulos de Configuración</h2>
            <p class="text-sm text-gray-500 mb-5">Accesos rápidos reservados para el rol Administrador.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $modulos = [
                        ['titulo' => 'Organización y Licencia', 'desc' => 'Datos corporativos, logotipo y vigencia de licencia.'],
                        ['titulo' => 'Sedes y Dependencias', 'desc' => 'Administración de sucursales, códigos y ubicaciones.'],
                        ['titulo' => 'Taquillas y Asignación', 'desc' => 'Control de módulos y operadores asignados.'],
                        ['titulo' => 'Servicios y Horarios', 'desc' => 'Prefijos de turno, duración y franjas de atención.'],
                        ['titulo' => 'Personal Staff y RBAC', 'desc' => 'Gestión de usuarios internos, roles y estados.'],
                        ['titulo' => 'Reglas de Prioridad', 'desc' => 'Pesos y criterios para ordenamiento de colas.'],
                    ];
                @endphp

                @foreach ($modulos as $modulo)
                    <div class="rounded-xl border border-gray-200 hover:border-[#7C3AED]/50 bg-[#F9FAFB] p-4 flex flex-col justify-between transition-colors">
                        <div>
                            <h3 class="text-sm font-semibold text-[#111827]">{{ $modulo['titulo'] }}</h3>
                            <p class="text-xs text-gray-600 mt-1">{{ $modulo['desc'] }}</p>
                        </div>
                        <div class="mt-4">
                            <button
                                type="button"
                                onclick="alert('Módulo {{ $modulo['titulo'] }} preparado para el siguiente sprint CRUD.')"
                                class="text-xs font-semibold text-[#7C3AED] hover:text-violet-800 transition-colors"
                            >
                                Gestionar módulo &rarr;
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
