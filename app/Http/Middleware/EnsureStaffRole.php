<?php

namespace App\Http\Middleware;

use App\Models\UsuarioStaff;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaffRole
{
    /**
     * Verifica que exista una sesión activa en el guard 'staff', que el usuario esté activo
     * y que su rol coincida con alguno de los roles permitidos.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::guard('staff')->check()) {
            abort(403, 'Acceso restringido al personal autorizado.');
        }

        /** @var UsuarioStaff|null $user */
        $user = Auth::guard('staff')->user();

        if (! $user || $user->estado !== 'activo') {
            abort(403, 'Tu cuenta de personal no se encuentra activa.');
        }

        $user->loadMissing('rol');

        if (! $user->rol) {
            abort(403, 'El usuario no tiene un rol asignado.');
        }

        if ($roles !== []) {
            $hasAllowedRole = false;

            foreach ($roles as $role) {
                if ($user->hasRole(trim($role))) {
                    $hasAllowedRole = true;
                    break;
                }
            }

            if (! $hasAllowedRole) {
                abort(403, 'No tienes permisos suficientes para acceder a este recurso.');
            }
        }

        return $next($request);
    }
}
