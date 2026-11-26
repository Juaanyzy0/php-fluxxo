<?php

namespace App\Http\Controllers;

use App\Models\UsuarioStaff;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AuthStaffController extends Controller
{
    public function __construct(
        private readonly OtpService $otpService,
    ) {}

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('staff')->check() || Auth::guard('cliente')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.staff-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo electrónico institucional es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $staff = UsuarioStaff::with('rol')
            ->where('email', strtolower(trim($validated['email'])))
            ->first();

        if (! $staff || ! Hash::check($validated['password'], $staff->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Credenciales inválidas. Verifica tu correo electrónico y contraseña.');
        }

        if ($staff->estado !== 'activo') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Tu cuenta de personal no está activa. Contacta al administrador del sistema.');
        }

        try {
            $this->otpService->generarYEnviarStaff($staff);
        } catch (Throwable $exception) {
            Log::error('Error enviando correo OTP de Staff vía SMTP/Mailtrap.', [
                'staff_id' => $staff->id,
                'email' => $staff->email,
                'error' => $exception->getMessage(),
            ]);

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'No se pudo enviar el código de verificación al correo. Verifica las credenciales SMTP de Mailtrap en tu archivo .env.');
        }

        $request->session()->put('pending_staff_id', $staff->id);

        return redirect()
            ->route('staff.verificar-otp')
            ->with('info', "Hemos enviado un código de verificación de 6 dígitos a {$staff->email}. Revisa tu buzón en Mailtrap Sandbox.");
    }

    public function showVerifyOtp(Request $request): View|RedirectResponse
    {
        if (Auth::guard('staff')->check() || Auth::guard('cliente')->check()) {
            return redirect()->route('dashboard');
        }

        $staff = $this->resolvePendingStaff($request);

        if (! $staff) {
            return redirect()
                ->route('staff.login')
                ->with('error', 'Inicia sesión primero para recibir tu código de verificación.');
        }

        return view('auth.staff-otp', [
            'staff' => $staff,
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo' => ['required', 'digits:6'],
        ], [
            'codigo.required' => 'Ingresa el código de verificación de 6 dígitos.',
            'codigo.digits' => 'El código de verificación debe tener exactamente 6 dígitos numéricos.',
        ]);

        $staff = $this->resolvePendingStaff($request);

        if (! $staff) {
            return redirect()
                ->route('staff.login')
                ->with('error', 'Tu sesión de verificación expiró. Inicia sesión nuevamente.');
        }

        if ($staff->estado !== 'activo') {
            $request->session()->forget('pending_staff_id');

            return redirect()
                ->route('staff.login')
                ->with('error', 'Tu cuenta de personal ya no se encuentra activa.');
        }

        if (! $this->otpService->validarStaff($staff->id, $validated['codigo'])) {
            return back()->with('error', 'El código ingresado es incorrecto o ha expirado. Revisa tu correo o solicita uno nuevo.');
        }

        $staff->loadMissing('rol');

        $request->session()->forget('pending_staff_id');
        Auth::guard('cliente')->logout();
        Auth::guard('staff')->login($staff);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('success', "Bienvenido, {$staff->nombre}. Sesión iniciada como {$staff->rol?->nombre}.");
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $staff = $this->resolvePendingStaff($request);

        if (! $staff) {
            return redirect()
                ->route('staff.login')
                ->with('error', 'Tu sesión de verificación expiró. Inicia sesión nuevamente.');
        }

        try {
            $this->otpService->generarYEnviarStaff($staff);
        } catch (Throwable $exception) {
            Log::error('Error reenviando correo OTP de Staff vía SMTP/Mailtrap.', [
                'staff_id' => $staff->id,
                'email' => $staff->email,
                'error' => $exception->getMessage(),
            ]);

            return back()->with('error', 'No se pudo reenviar el correo. Verifica la configuración SMTP de Mailtrap en .env.');
        }

        return redirect()
            ->route('staff.verificar-otp')
            ->with('info', "Hemos reenviado un nuevo código de verificación de 6 dígitos a {$staff->email}.");
    }

    private function resolvePendingStaff(Request $request): ?UsuarioStaff
    {
        $staffId = $request->session()->get('pending_staff_id');

        if (! $staffId) {
            return null;
        }

        return UsuarioStaff::with('rol')->find($staffId);
    }
}
