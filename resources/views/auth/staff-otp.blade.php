@extends('layouts.app')

@section('title', 'Verificar Código Staff — FLUXXO')

@section('content')
    <div class="max-w-md mx-auto py-4 sm:py-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="h-1.5 bg-[#7C3AED]"></div>

            <div class="p-6 sm:p-8">
                <div class="mb-6 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#7C3AED]/10 text-[#7C3AED] mb-3">
                        Verificación en Dos Pasos (Staff)
                    </span>
                    <h1 class="text-2xl font-bold text-[#111827] tracking-tight">Verifica tu correo</h1>
                    <p class="mt-1.5 text-sm text-gray-600">
                        Ingresa el código de 6 dígitos enviado a
                        <span class="font-semibold text-[#111827]">{{ $staff->email }}</span>.
                        Expira en 5 minutos.
                    </p>
                </div>

                <form id="otp-staff-form" method="POST" action="{{ route('staff.verificar-otp.post') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="codigo" id="otp-staff-hidden" value="">

                    <div class="flex items-center justify-center gap-2 sm:gap-3" data-otp-group>
                        @for ($i = 0; $i < 6; $i++)
                            <input
                                type="text"
                                inputmode="numeric"
                                maxlength="1"
                                pattern="[0-9]"
                                autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                                aria-label="Dígito {{ $i + 1 }}"
                                data-otp-digit
                                class="w-11 h-12 sm:w-12 sm:h-14 text-center text-xl font-bold text-[#111827] rounded-lg border border-gray-300 bg-white focus:border-[#7C3AED] focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/25 transition-colors"
                            >
                        @endfor
                    </div>

                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center rounded-lg bg-[#7C3AED] hover:bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors"
                    >
                        Verificar y acceder al panel
                    </button>
                </form>

                <div class="mt-5 flex flex-col items-center gap-3 pt-5 border-t border-gray-100">
                    <form method="POST" action="{{ route('staff.reenviar-otp') }}">
                        @csrf
                        <button
                            type="submit"
                            class="text-sm font-semibold text-[#7C3AED] hover:text-violet-800 transition-colors"
                        >
                            Reenviar código al correo
                        </button>
                    </form>

                    <a href="{{ route('staff.login') }}" class="text-xs text-gray-500 hover:text-[#111827] transition-colors">
                        &larr; Volver al inicio de sesión Staff
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const inputs = Array.from(document.querySelectorAll('[data-otp-digit]'));
        const hiddenInput = document.getElementById('otp-staff-hidden');
        const form = document.getElementById('otp-staff-form');

        if (!inputs.length || !hiddenInput || !form) return;

        const syncHidden = () => {
            hiddenInput.value = inputs.map((el) => el.value).join('');
        };

        inputs[0].focus();

        inputs.forEach((input, idx) => {
            input.addEventListener('input', (event) => {
                const digits = event.target.value.replace(/\D/g, '');
                if (!digits) {
                    event.target.value = '';
                    syncHidden();
                    return;
                }

                if (digits.length > 1) {
                    digits.slice(0, 6 - idx).split('').forEach((char, offset) => {
                        if (inputs[idx + offset]) {
                            inputs[idx + offset].value = char;
                        }
                    });
                    const nextIndex = Math.min(idx + digits.length, inputs.length - 1);
                    inputs[nextIndex].focus();
                } else {
                    event.target.value = digits;
                    if (idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                    }
                }
                syncHidden();
            });

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && !input.value && idx > 0) {
                    inputs[idx - 1].value = '';
                    inputs[idx - 1].focus();
                    syncHidden();
                } else if (event.key === 'ArrowLeft' && idx > 0) {
                    inputs[idx - 1].focus();
                } else if (event.key === 'ArrowRight' && idx < inputs.length - 1) {
                    inputs[idx + 1].focus();
                }
            });

            input.addEventListener('paste', (event) => {
                const pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                if (!pasted) return;
                event.preventDefault();
                pasted.slice(0, 6).split('').forEach((char, i) => {
                    if (inputs[i]) inputs[i].value = char;
                });
                const focusIndex = Math.min(pasted.length, inputs.length - 1);
                inputs[focusIndex].focus();
                syncHidden();
            });
        });

        form.addEventListener('submit', syncHidden);
    })();
</script>
@endpush
