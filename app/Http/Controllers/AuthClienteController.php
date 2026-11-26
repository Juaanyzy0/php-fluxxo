<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthClienteController extends Controller
{
    public function __construct(
        private readonly OtpService $otpService,
    ) {}

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('cliente')->check() || Auth::guard('staff')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.cliente-login');
    }

    public function solicitarOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cedula' => ['required', 'digits_between:5,20'],
            'nombre' => ['required', 'string', 'min:3', 'max:150'],
            'telefono' => ['required', 'digits:10'],
        ], [
            'cedula.required' => 'La cédula o documento de identidad es obligatorio.',
            'cedula.digits_between' => 'La cédula debe contener únicamente números (entre 5 y 20 dígitos).',
            'nombre.required' => 'Tus nombres y apellidos son obligatorios.',
            'nombre.min' => 'Ingresa al menos 3 caracteres en tu nombre completo.',
            'telefono.required' => 'El número de celular es obligatorio.',
            'telefono.digits' => 'El número de celular debe tener exactamente 10 dígitos numéricos.',
        ]);

        $cliente = Cliente::firstOrCreate(
            ['cedula' => $validated['cedula']],
            [
                'nombre' => trim($validated['nombre']),
                'telefono' => $validated['telefono'],
                'canal_notificacion_preferido' => 'sms',
                'puntaje_reputacion' => 100,
                'estado' => 'activo',
                'fecha_registro' => now(),
            ]
        );

        if (! $cliente->wasRecentlyCreated) {
            $cliente->update([
                'nombre' => trim($validated['nombre']),
                'telefono' => $validated['telefono'],
            ]);
        }

        if ($cliente->estado === 'inactivo') {
            return back()
                ->withInput()
                ->with('error', 'Tu cuenta de cliente se encuentra inactiva. Comunícate con la recepción.');
        }

        if ($cliente->bloqueado_hasta !== null && $cliente->bloqueado_hasta->isFuture()) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Tu cuenta se encuentra bloqueada temporalmente hasta el '
                    .$cliente->bloqueado_hasta->format('d/m/Y H:i').'.'
                );
        }

        $codigo = $this->otpService->generarCliente($cliente);

        $request->session()->put('pending_cliente_id', $cliente->id);

        if ($this->otpService->isSmsSimulationEnabled()) {
            return redirect()
                ->route('cliente.verificar-otp')
                ->with('success', "📱 [SMS SIMULADO] Tu código de verificación FLUXXO es: {$codigo}");
        }

        return redirect()
            ->route('cliente.verificar-otp')
            ->with('info', 'Hemos enviado un código de verificación de 6 dígitos por SMS a tu celular.');
    }

    public function showVerifyOtp(Request $request): View|RedirectResponse
    {
        if (Auth::guard('cliente')->check() || Auth::guard('staff')->check()) {
            return redirect()->route('dashboard');
        }

        $cliente = $this->resolvePendingCliente($request);

        if (! $cliente) {
            return redirect()
                ->route('cliente.login')
                ->with('error', 'Debes ingresar tus datos para solicitar un código de verificación.');
        }

        return view('auth.cliente-otp', [
            'cliente' => $cliente,
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

        $cliente = $this->resolvePendingCliente($request);

        if (! $cliente) {
            return redirect()
                ->route('cliente.login')
                ->with('error', 'Tu sesión de verificación expiró. Solicita un nuevo código.');
        }

        if ($cliente->bloqueado_hasta !== null && $cliente->bloqueado_hasta->isFuture()) {
            $request->session()->forget('pending_cliente_id');

            return redirect()
                ->route('cliente.login')
                ->with('error', 'Tu cuenta se encuentra bloqueada temporalmente.');
        }

        if (! $this->otpService->validarCliente($cliente->id, $validated['codigo'])) {
            return back()->with('error', 'El código ingresado es incorrecto o ha expirado. Inténtalo nuevamente.');
        }

        $request->session()->forget('pending_cliente_id');
        Auth::guard('staff')->logout();
        Auth::guard('cliente')->login($cliente);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('success', "¡Bienvenido, {$cliente->nombre}! Has ingresado correctamente.");
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $cliente = $this->resolvePendingCliente($request);

        if (! $cliente) {
            return redirect()
                ->route('cliente.login')
                ->with('error', 'Tu sesión de verificación expiró. Ingresa tus datos nuevamente.');
        }

        $codigo = $this->otpService->generarCliente($cliente);

        if ($this->otpService->isSmsSimulationEnabled()) {
            return redirect()
                ->route('cliente.verificar-otp')
                ->with('success', "📱 [SMS SIMULADO] Tu código de verificación FLUXXO es: {$codigo}");
        }

        return redirect()
            ->route('cliente.verificar-otp')
            ->with('info', 'Hemos reenviado un nuevo código de verificación por SMS a tu celular.');
    }

    private function resolvePendingCliente(Request $request): ?Cliente
    {
        $clienteId = $request->session()->get('pending_cliente_id');

        if (! $clienteId) {
            return null;
        }

        return Cliente::find($clienteId);
    }
}
