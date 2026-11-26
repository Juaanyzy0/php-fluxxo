@extends('layouts.app')

@section('title', 'Acceso Staff — FLUXXO')

@section('content')
    <div class="max-w-md mx-auto py-4 sm:py-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="h-1.5 bg-[#7C3AED]"></div>

            <div class="p-6 sm:p-8">
                <div class="mb-6">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#7C3AED]/10 text-[#7C3AED] mb-3">
                        Acceso Personal &amp; Administración
                    </span>
                    <h1 class="text-2xl font-bold text-[#111827] tracking-tight">Iniciar sesión como Staff</h1>
                    <p class="mt-1.5 text-sm text-gray-600">
                        Ingresa tus credenciales institucionales. Te enviaremos un código de seguridad de 6 dígitos a tu correo registrado.
                    </p>
                </div>

                <form method="POST" action="{{ route('staff.login.post') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-[#111827] mb-1.5">
                            Correo Electrónico Institucional
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="ejemplo@fluxxo.com"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-[#111827] placeholder-gray-400 focus:border-[#7C3AED] focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-[#111827] mb-1.5">
                            Contraseña
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-[#111827] placeholder-gray-400 focus:border-[#7C3AED] focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-[#7C3AED] hover:bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors"
                    >
                        Continuar y enviar código OTP
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                    <a
                        href="{{ route('cliente.login') }}"
                        class="text-xs font-medium text-gray-500 hover:text-[#10B981] transition-colors"
                    >
                        &larr; Volver al portal de clientes
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
