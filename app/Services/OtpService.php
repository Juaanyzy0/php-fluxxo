<?php

namespace App\Services;

use App\Mail\CodigoOtpStaffMail;
use App\Models\Cliente;
use App\Models\UsuarioStaff;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    private const EXPIRATION_MINUTES = 5;

    /**
     * Genera un código OTP de 6 dígitos para el usuario Staff, lo guarda en caché por 5 minutos
     * y lo envía de forma síncrona por correo electrónico (Mailtrap Sandbox).
     */
    public function generarYEnviarStaff(UsuarioStaff $staff): string
    {
        $codigo = (string) random_int(100000, 999999);

        Cache::put(
            $this->staffCacheKey($staff->id),
            $codigo,
            now()->addMinutes(self::EXPIRATION_MINUTES)
        );

        Mail::to($staff->email)->send(new CodigoOtpStaffMail($staff, $codigo));

        return $codigo;
    }

    /**
     * Valida el código OTP de un usuario Staff y lo elimina de caché tras un uso exitoso.
     */
    public function validarStaff(int $staffId, string $codigo): bool
    {
        $cacheKey = $this->staffCacheKey($staffId);
        $cachedCode = Cache::get($cacheKey);

        if (! is_string($cachedCode) || ! hash_equals($cachedCode, trim($codigo))) {
            return false;
        }

        Cache::forget($cacheKey);

        return true;
    }

    /**
     * Genera un código OTP de 6 dígitos para un Cliente y lo guarda en caché por 5 minutos.
     */
    public function generarCliente(Cliente $cliente): string
    {
        $codigo = (string) random_int(100000, 999999);

        Cache::put(
            $this->clienteCacheKey($cliente->id),
            $codigo,
            now()->addMinutes(self::EXPIRATION_MINUTES)
        );

        return $codigo;
    }

    /**
     * Valida el código OTP de un Cliente y lo elimina de caché tras un uso exitoso.
     */
    public function validarCliente(int $clienteId, string $codigo): bool
    {
        $cacheKey = $this->clienteCacheKey($clienteId);
        $cachedCode = Cache::get($cacheKey);

        if (! is_string($cachedCode) || ! hash_equals($cachedCode, trim($codigo))) {
            return false;
        }

        Cache::forget($cacheKey);

        return true;
    }

    /**
     * Indica si la simulación de SMS OTP en pantalla está habilitada.
     */
    public function isSmsSimulationEnabled(): bool
    {
        return (bool) config('services.otp_sms_simulation', env('OTP_SMS_SIMULATION', true));
    }

    private function staffCacheKey(int $staffId): string
    {
        return "otp_staff_{$staffId}";
    }

    private function clienteCacheKey(int $clienteId): string
    {
        return "otp_cliente_{$clienteId}";
    }
}
