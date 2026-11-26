<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FLUXXO — Gestión Inteligente de Turnos')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
                    },
                    colors: {
                        fluxxo: {
                            emerald: '#10B981',
                            purple: '#7C3AED',
                            dark: '#111827',
                            surface: '#F9FAFB',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-[#F9FAFB] text-[#111827] font-sans antialiased flex flex-col">
    @php
        $staffAuth = \Illuminate\Support\Facades\Auth::guard('staff')->user();
        $clienteAuth = \Illuminate\Support\Facades\Auth::guard('cliente')->user();
        if ($staffAuth) {
            $staffAuth->loadMissing('rol');
        }
    @endphp

    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <a href="{{ ($staffAuth || $clienteAuth) ? route('dashboard') : route('cliente.login') }}" class="flex items-center gap-3 group">
                <svg class="w-9 h-9 shrink-0" viewBox="0 0 200 200" role="img" aria-label="Fluxxo logo">
                    <path fill="#111827" fill-rule="evenodd" d="M36 36H164V164H124V84Q124 76 116 76H84Q76 76 76 84V164H36Z"/>
                    <rect x="12" y="108" width="176" height="20" fill="#10B981"/>
                    <rect x="168" y="108" width="20" height="20" fill="#7C3AED"/>
                </svg>
                <div class="leading-tight">
                    <span class="block text-lg font-bold tracking-wider text-[#111827]">FLUXXO</span>
                    <span class="block text-[11px] font-medium text-gray-500">Sistema Inteligente de Turnos</span>
                </div>
            </a>

            @if ($staffAuth)
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-[#111827] leading-tight">{{ $staffAuth->nombre }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 mt-0.5 rounded-full text-xs font-medium bg-[#7C3AED]/10 text-[#7C3AED]">
                            {{ $staffAuth->rol?->nombre ?? 'Staff' }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition-colors"
                        >
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            @elseif ($clienteAuth)
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-[#111827] leading-tight">{{ $clienteAuth->nombre }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 mt-0.5 rounded-full text-xs font-medium bg-[#10B981]/10 text-[#10B981]">
                            Cliente
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition-colors"
                        >
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center gap-2 text-xs font-medium">
                    @if (request()->routeIs('staff.*'))
                        <span class="px-2.5 py-1 rounded-full bg-[#7C3AED]/10 text-[#7C3AED]">Acceso Personal</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full bg-[#10B981]/10 text-[#10B981]">Portal Clientes</span>
                    @endif
                </div>
            @endif
        </div>
    </header>

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Banners de alertas --}}
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-900 shadow-sm flex items-start gap-3" role="alert">
                <span class="inline-block w-2.5 h-2.5 mt-1.5 rounded-full bg-[#10B981] shrink-0"></span>
                <div class="flex-1">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm font-medium text-red-900 shadow-sm flex items-start gap-3" role="alert">
                <span class="inline-block w-2.5 h-2.5 mt-1.5 rounded-full bg-red-500 shrink-0"></span>
                <div class="flex-1">{{ session('error') }}</div>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 rounded-xl border border-violet-200 bg-violet-50 px-4 py-3.5 text-sm font-medium text-violet-900 shadow-sm flex items-start gap-3" role="status">
                <span class="inline-block w-2.5 h-2.5 mt-1.5 rounded-full bg-[#7C3AED] shrink-0"></span>
                <div class="flex-1">{{ session('info') }}</div>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-900 shadow-sm" role="alert">
                <p class="font-semibold mb-1">Revisa los siguientes campos:</p>
                <ul class="list-disc list-inside space-y-0.5 text-red-800">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-gray-200 bg-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-500">
            <span>&copy; {{ date('Y') }} FLUXXO &mdash; Gestión Inteligente de Turnos</span>
            <span>Autenticación protegida por OTP y control de acceso por roles (RBAC)</span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
