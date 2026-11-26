<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Dependencia;
use App\Models\HorarioAtencion;
use App\Models\ReglaPrioridad;
use App\Models\Servicio;
use App\Models\Taquilla;
use App\Models\Turno;
use App\Models\UsuarioStaff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Auth::guard('staff')->check()) {
            /** @var UsuarioStaff $staff */
            $staff = Auth::guard('staff')->user();
            $staff->loadMissing(['rol', 'organizacion']);

            if ($staff->estado !== 'activo') {
                Auth::guard('staff')->logout();

                return redirect()
                    ->route('staff.login')
                    ->with('error', 'Tu cuenta de personal se encuentra inactiva.');
            }

            $rolNombre = $staff->rol?->nombre;

            return match ($rolNombre) {
                'Administrador' => $this->renderAdminDashboard($staff),
                'Moderador' => $this->renderModeradorDashboard($staff),
                'Operador' => $this->renderOperadorDashboard($staff),
                default => abort(403, 'Tu rol no tiene un panel asignado.'),
            };
        }

        if (Auth::guard('cliente')->check()) {
            /** @var Cliente $cliente */
            $cliente = Auth::guard('cliente')->user();

            return $this->renderClienteDashboard($cliente);
        }

        return redirect()->route('cliente.login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('staff')->logout();
        Auth::guard('cliente')->logout();

        $request->session()->forget(['pending_cliente_id', 'pending_staff_id']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('cliente.login')
            ->with('info', 'Has cerrado sesión correctamente.');
    }

    private function renderClienteDashboard(Cliente $cliente): View
    {
        $turnoActivo = Turno::with(['servicio.dependencia', 'taquilla', 'reglaPrioridad', 'confirmacionAsistencia'])
            ->where('cliente_id', $cliente->id)
            ->whereIn('estado', ['reservado', 'confirmado', 'en_espera', 'llamado', 'en_atencion'])
            ->latest('fecha_solicitud')
            ->first();

        $historialReciente = Turno::with(['servicio', 'taquilla'])
            ->where('cliente_id', $cliente->id)
            ->whereIn('estado', ['finalizado', 'cancelado', 'ausente'])
            ->latest('fecha_solicitud')
            ->take(5)
            ->get();

        return view('dashboards.cliente', [
            'cliente' => $cliente,
            'turnoActivo' => $turnoActivo,
            'historialReciente' => $historialReciente,
        ]);
    }

    private function renderAdminDashboard(UsuarioStaff $staff): View
    {
        $organizacionId = $staff->organizacion_id;

        $totalSedes = Dependencia::where('organizacion_id', $organizacionId)->count();
        $totalTaquillas = Taquilla::whereHas('dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))->count();
        $totalServicios = Servicio::whereHas('dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))->count();
        $totalStaff = UsuarioStaff::where('organizacion_id', $organizacionId)->count();
        $turnosHoy = Turno::whereHas('servicio.dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))
            ->whereDate('fecha_solicitud', today())
            ->count();
        $turnosActivos = Turno::whereHas('servicio.dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))
            ->whereIn('estado', ['reservado', 'confirmado', 'en_espera', 'llamado', 'en_atencion'])
            ->count();

        return view('dashboards.admin', [
            'staff' => $staff,
            'organizacion' => $staff->organizacion,
            'metricas' => [
                'sedes' => $totalSedes,
                'taquillas' => $totalTaquillas,
                'servicios' => $totalServicios,
                'staff' => $totalStaff,
                'turnos_hoy' => $turnosHoy,
                'turnos_activos' => $turnosActivos,
            ],
        ]);
    }

    private function renderModeradorDashboard(UsuarioStaff $staff): View
    {
        $organizacionId = $staff->organizacion_id;

        $sedes = Dependencia::withCount(['taquillas', 'servicios'])
            ->where('organizacion_id', $organizacionId)
            ->orderBy('nombre')
            ->get();

        $totalHorariosActivos = HorarioAtencion::where('estado', 'activo')
            ->whereHas('servicio.dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))
            ->count();

        $reglasPrioridad = ReglaPrioridad::where('organizacion_id', $organizacionId)
            ->orderByDesc('peso_prioridad')
            ->get();

        $turnosRecientes = Turno::with(['servicio.dependencia', 'cliente', 'taquilla', 'reglaPrioridad'])
            ->whereHas('servicio.dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))
            ->latest('fecha_solicitud')
            ->take(10)
            ->get();

        $resumenEstados = [
            'en_espera' => Turno::whereHas('servicio.dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))
                ->where('estado', 'en_espera')->count(),
            'llamado' => Turno::whereHas('servicio.dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))
                ->where('estado', 'llamado')->count(),
            'en_atencion' => Turno::whereHas('servicio.dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))
                ->where('estado', 'en_atencion')->count(),
            'finalizado' => Turno::whereHas('servicio.dependencia', fn ($q) => $q->where('organizacion_id', $organizacionId))
                ->where('estado', 'finalizado')->count(),
        ];

        return view('dashboards.moderador', [
            'staff' => $staff,
            'sedes' => $sedes,
            'totalHorariosActivos' => $totalHorariosActivos,
            'reglasPrioridad' => $reglasPrioridad,
            'turnosRecientes' => $turnosRecientes,
            'resumenEstados' => $resumenEstados,
        ]);
    }

    private function renderOperadorDashboard(UsuarioStaff $staff): View
    {
        $taquilla = Taquilla::with('dependencia')
            ->where('usuario_staff_id', $staff->id)
            ->first();

        $turnoEnAtencion = Turno::with(['cliente', 'servicio', 'reglaPrioridad'])
            ->where('usuario_staff_id', $staff->id)
            ->where('estado', 'en_atencion')
            ->latest('fecha_inicio_atencion')
            ->first();

        $turnosCola = Turno::with(['cliente', 'servicio.dependencia', 'taquilla', 'reglaPrioridad'])
            ->whereIn('estado', ['en_espera', 'llamado'])
            ->whereHas('servicio.dependencia', fn ($q) => $q->where('organizacion_id', $staff->organizacion_id))
            ->orderByRaw("CASE estado WHEN 'llamado' THEN 0 ELSE 1 END")
            ->orderByDesc('puntaje_prioridad')
            ->orderBy('fecha_solicitud')
            ->get();

        return view('dashboards.operador', [
            'staff' => $staff,
            'taquilla' => $taquilla,
            'turnoEnAtencion' => $turnoEnAtencion,
            'turnosCola' => $turnosCola,
        ]);
    }
}
