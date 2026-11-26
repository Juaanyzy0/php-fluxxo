@extends('layouts.app')

@section('title', 'Ingreso Clientes — FLUXXO')

@section('content')
    <div class="max-w-md mx-auto py-4 sm:py-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="h-1.5 bg-[#10B981]"></div>

            <div class="p-6 sm:p-8">
                <div class="mb-6">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#10B981]/10 text-[#10B981] mb-3">
                        Portal de Atención al Cliente
                    </span>
                    <h1 class="text-2xl font-bold text-[#111827] tracking-tight">Solicita y gestiona tu turno</h1>
                    <p class="mt-1.5 text-sm text-gray-600">
                        Ingresa tus datos para recibir un código de verificación por SMS. Si es tu primera visita, crearemos tu perfil automáticamente.
                    </p>
                </div>

                <form method="POST" action="{{ route('cliente.solicitar-otp') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="cedula" class="block text-sm font-medium text-[#111827] mb-1.5">
                            Cédula / Documento
                        </label>
                        <input
                            id="cedula"
                            name="cedula"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            value="{{ old('cedula') }}"
                            required
                            autocomplete="off"
                            placeholder="Ej. 1018452301"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-[#111827] placeholder-gray-400 focus:border-[#10B981] focus:outline-none focus:ring-2 focus:ring-[#10B981]/20"
                        >
                    </div>

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-[#111827] mb-1.5">
                            Nombres y Apellidos
                        </label>
                        <input
                            id="nombre"
                            name="nombre"
                            type="text"
                            value="{{ old('nombre') }}"
                            required
                            autocomplete="name"
                            placeholder="Ej. María Fernanda Rojas"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-[#111827] placeholder-gray-400 focus:border-[#10B981] focus:outline-none focus:ring-2 focus:ring-[#10B981]/20"
                        >
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-[#111827] mb-1.5">
                            Número de Celular (10 dígitos)
                        </label>
                        <input
                            id="telefono"
                            name="telefono"
                            type="tel"
                            inputmode="numeric"
                            maxlength="10"
                            pattern="[0-9]{10}"
                            value="{{ old('telefono') }}"
                            required
                            autocomplete="tel"
                            placeholder="Ej. 3104567890"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-[#111827] placeholder-gray-400 focus:border-[#10B981] focus:outline-none focus:ring-2 focus:ring-[#10B981]/20"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-[#10B981] hover:bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors"
                    >
                        Enviar código de verificación por SMS
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                    <a
                        href="{{ route('staff.login') }}"
                        class="text-xs font-medium text-gray-500 hover:text-[#7C3AED] transition-colors"
                    >
                        ¿Eres personal de la institución? <span class="text-[#7C3AED] font-semibold underline underline-offset-2">Acceso Staff</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
