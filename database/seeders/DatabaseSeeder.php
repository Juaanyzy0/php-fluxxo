<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\ConfirmacionAsistencia;
use App\Models\Dependencia;
use App\Models\HorarioAtencion;
use App\Models\Notificacion;
use App\Models\Organizacion;
use App\Models\RegistroHistorico;
use App\Models\ReglaPrioridad;
use App\Models\Rol;
use App\Models\Servicio;
use App\Models\Taquilla;
use App\Models\Turno;
use App\Models\UsuarioStaff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now();

        // 1. Organización
        $organizacion = Organizacion::create([
            'nit' => '900123456-1',
            'nombre' => 'Clínica Fluxxo Central',
            'telefono' => '6015550100',
            'correo' => 'contacto@fluxxo.com',
            'direccion' => 'Av. El Dorado # 68B-85, Bogotá D.C.',
            'licencia_codigo' => 'FLX-2026-CENTRAL-001',
            'licencia_vencimiento' => $now->copy()->addYear()->toDateString(),
            'logo_url' => null,
            'estado' => 'activa',
        ]);

        // 2. Roles (RBAC)
        $rolAdmin = Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Acceso total al sistema, gestión de organizaciones, sedes, roles, servicios y configuración.',
            'estado' => 'activo',
            'fecha_creacion' => $now,
        ]);

        $rolModerador = Rol::create([
            'nombre' => 'Moderador',
            'descripcion' => 'Supervisión operativa de colas, taquillas, reglas de prioridad y reportes de atención.',
            'estado' => 'activo',
            'fecha_creacion' => $now,
        ]);

        $rolOperador = Rol::create([
            'nombre' => 'Operador',
            'descripcion' => 'Atención de turnos en taquilla: llamar, iniciar, finalizar o marcar ausencia de clientes.',
            'estado' => 'activo',
            'fecha_creacion' => $now,
        ]);

        // 3. Dependencias (Sedes)
        $sedeNorte = Dependencia::create([
            'organizacion_id' => $organizacion->id,
            'nombre' => 'Sede Norte',
            'codigo' => 'SN-01',
            'ubicacion' => 'Calle 127 # 15-40, Piso 1',
            'telefono' => '6015550101',
            'estado' => 'activa',
        ]);

        $sedeCentro = Dependencia::create([
            'organizacion_id' => $organizacion->id,
            'nombre' => 'Sede Centro',
            'codigo' => 'SC-01',
            'ubicacion' => 'Carrera 7 # 32-16, Local 102',
            'telefono' => '6015550102',
            'estado' => 'activa',
        ]);

        // 4. Usuarios Staff (uno por cada rol)
        $passwordHash = Hash::make('password123');

        $admin = UsuarioStaff::create([
            'organizacion_id' => $organizacion->id,
            'rol_id' => $rolAdmin->id,
            'cedula' => '1000000001',
            'nombre' => 'Carlos Andrés Mendoza',
            'email' => 'admin@fluxxo.com',
            'telefono' => '3001110001',
            'password' => $passwordHash,
            'estado' => 'activo',
            'fecha_registro' => $now,
        ]);

        $moderador = UsuarioStaff::create([
            'organizacion_id' => $organizacion->id,
            'rol_id' => $rolModerador->id,
            'cedula' => '1000000002',
            'nombre' => 'Laura Marcela Gómez',
            'email' => 'moderador@fluxxo.com',
            'telefono' => '3001110002',
            'password' => $passwordHash,
            'estado' => 'activo',
            'fecha_registro' => $now,
        ]);

        $operador = UsuarioStaff::create([
            'organizacion_id' => $organizacion->id,
            'rol_id' => $rolOperador->id,
            'cedula' => '1000000003',
            'nombre' => 'Julián Felipe Torres',
            'email' => 'operador@fluxxo.com',
            'telefono' => '3001110003',
            'password' => $passwordHash,
            'estado' => 'activo',
            'fecha_registro' => $now,
        ]);

        // 5. Taquillas
        $taquilla1 = Taquilla::create([
            'dependencia_id' => $sedeNorte->id,
            'usuario_staff_id' => $operador->id,
            'codigo' => 'T-01',
            'nombre' => 'Módulo 1',
            'estado' => 'ocupada',
        ]);

        $taquilla2 = Taquilla::create([
            'dependencia_id' => $sedeNorte->id,
            'usuario_staff_id' => null,
            'codigo' => 'T-02',
            'nombre' => 'Módulo 2',
            'estado' => 'disponible',
        ]);

        Taquilla::create([
            'dependencia_id' => $sedeCentro->id,
            'usuario_staff_id' => null,
            'codigo' => 'T-03',
            'nombre' => 'Módulo 3',
            'estado' => 'disponible',
        ]);

        // 6. Servicios y sus Horarios de Atención (Lunes a Viernes 08:00–17:00)
        $servicioGeneral = Servicio::create([
            'dependencia_id' => $sedeNorte->id,
            'nombre' => 'Atención General',
            'prefijo_turno' => 'A',
            'descripcion' => 'Consultas generales, orientación al usuario y radicación de solicitudes.',
            'duracion_min' => 15,
            'holgura_min' => 5,
            'estado' => 'activo',
        ]);

        $servicioCaja = Servicio::create([
            'dependencia_id' => $sedeCentro->id,
            'nombre' => 'Caja y Facturación',
            'prefijo_turno' => 'C',
            'descripcion' => 'Pagos de copagos, cuotas moderadoras y emisión de facturas.',
            'duracion_min' => 10,
            'holgura_min' => 3,
            'estado' => 'activo',
        ]);

        $servicioPrioritario = Servicio::create([
            'dependencia_id' => $sedeNorte->id,
            'nombre' => 'Atención Prioritaria',
            'prefijo_turno' => 'P',
            'descripcion' => 'Atención preferencial para adultos mayores, gestantes y personas con movilidad reducida.',
            'duracion_min' => 20,
            'holgura_min' => 5,
            'estado' => 'activo',
        ]);

        $diasLaborales = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        foreach ([$servicioGeneral, $servicioCaja, $servicioPrioritario] as $servicio) {
            foreach ($diasLaborales as $dia) {
                HorarioAtencion::create([
                    'servicio_id' => $servicio->id,
                    'dia_semana' => $dia,
                    'hora_inicio' => '08:00:00',
                    'hora_fin' => '17:00:00',
                    'cupos_por_franja' => 12,
                    'fecha_especifica' => null,
                    'es_especial' => false,
                    'estado' => 'activo',
                ]);
            }
        }

        // 7. Reglas de Prioridad y vinculación N:M en servicio_regla_prioridad
        $reglaGeneral = ReglaPrioridad::create([
            'organizacion_id' => $organizacion->id,
            'nombre' => 'General',
            'nivel' => 1,
            'peso_prioridad' => 1,
            'criterio' => 'Orden estándar de llegada',
            'descripcion' => 'Prioridad base aplicable a usuarios sin condición especial.',
            'estado' => 'activa',
        ]);

        $reglaPreferencial = ReglaPrioridad::create([
            'organizacion_id' => $organizacion->id,
            'nombre' => 'Adulto Mayor / Preferencial',
            'nivel' => 2,
            'peso_prioridad' => 10,
            'criterio' => 'Adulto mayor (>= 60 años), mujer gestante o persona con discapacidad',
            'descripcion' => 'Prioridad preferencial con mayor peso en el algoritmo de ordenamiento de cola.',
            'estado' => 'activa',
        ]);

        $servicioGeneral->reglasPrioridad()->attach([$reglaGeneral->id, $reglaPreferencial->id]);
        $servicioCaja->reglasPrioridad()->attach([$reglaGeneral->id, $reglaPreferencial->id]);
        $servicioPrioritario->reglasPrioridad()->attach([$reglaPreferencial->id]);

        // 8. Clientes de prueba (sin contraseña)
        $cliente1 = Cliente::create([
            'cedula' => '1018452301',
            'nombre' => 'María Fernanda Rojas',
            'telefono' => '3104567890',
            'correo' => 'maria.rojas@example.com',
            'canal_notificacion_preferido' => 'whatsapp',
            'puntaje_reputacion' => 100,
            'bloqueado_hasta' => null,
            'estado' => 'activo',
            'fecha_registro' => $now->copy()->subDays(15),
        ]);

        $cliente2 = Cliente::create([
            'cedula' => '79451238',
            'nombre' => 'Hernando Ramírez Castillo',
            'telefono' => '3157894561',
            'correo' => 'hernando.ramirez@example.com',
            'canal_notificacion_preferido' => 'sms',
            'puntaje_reputacion' => 98,
            'bloqueado_hasta' => null,
            'estado' => 'activo',
            'fecha_registro' => $now->copy()->subDays(30),
        ]);

        $cliente3 = Cliente::create([
            'cedula' => '1032489120',
            'nombre' => 'Andrés Camilo Herrera',
            'telefono' => '3209876543',
            'correo' => 'andres.herrera@example.com',
            'canal_notificacion_preferido' => 'correo',
            'puntaje_reputacion' => 95,
            'bloqueado_hasta' => null,
            'estado' => 'activo',
            'fecha_registro' => $now->copy()->subDays(7),
        ]);

        // 9. 4 Turnos en distintos estados (finalizado, en_atencion, llamado, en_espera)
        // Turno 1: Finalizado
        $turnoFinalizado = Turno::create([
            'servicio_id' => $servicioGeneral->id,
            'cliente_id' => $cliente1->id,
            'usuario_staff_id' => $operador->id,
            'taquilla_id' => $taquilla1->id,
            'regla_prioridad_id' => $reglaGeneral->id,
            'codigo_turno' => 'A-001',
            'origen_solicitud' => 'remoto',
            'puntaje_prioridad' => $reglaGeneral->peso_prioridad,
            'posicion_cola' => 1,
            'estado' => 'finalizado',
            'fecha_solicitud' => $now->copy()->subMinutes(60),
            'fecha_llamado' => $now->copy()->subMinutes(45),
            'fecha_inicio_atencion' => $now->copy()->subMinutes(43),
            'fecha_fin_atencion' => $now->copy()->subMinutes(28),
        ]);

        ConfirmacionAsistencia::create([
            'turno_id' => $turnoFinalizado->id,
            'confirmado' => true,
            'fecha_confirmacion' => $now->copy()->subMinutes(55),
            'metodo_confirmacion' => 'whatsapp',
            'token_checkin' => 'CHK-A001-20261126',
            'hora_checkin' => $now->copy()->subMinutes(48),
            'observacion' => 'Cliente realizó check-in puntual en recepción.',
        ]);

        Notificacion::create([
            'turno_id' => $turnoFinalizado->id,
            'tipo' => 'llamado_taquilla',
            'canal' => 'whatsapp',
            'mensaje' => 'Turno A-001: Por favor acérquese al Módulo 1 (T-01).',
            'fecha_envio' => $now->copy()->subMinutes(45),
            'estado_envio' => 'enviado',
        ]);

        RegistroHistorico::create([
            'turno_id' => $turnoFinalizado->id,
            'usuario_staff_id' => $operador->id,
            'fecha' => $now->copy()->subMinutes(28),
            'accion' => 'finalizacion_turno',
            'descripcion' => 'Atención general completada satisfactoriamente en Módulo 1.',
            'resultado' => 'exitoso',
        ]);

        // Turno 2: En atención
        $turnoEnAtencion = Turno::create([
            'servicio_id' => $servicioPrioritario->id,
            'cliente_id' => $cliente2->id,
            'usuario_staff_id' => $operador->id,
            'taquilla_id' => $taquilla1->id,
            'regla_prioridad_id' => $reglaPreferencial->id,
            'codigo_turno' => 'P-001',
            'origen_solicitud' => 'remoto',
            'puntaje_prioridad' => $reglaPreferencial->peso_prioridad,
            'posicion_cola' => 1,
            'estado' => 'en_atencion',
            'fecha_solicitud' => $now->copy()->subMinutes(30),
            'fecha_llamado' => $now->copy()->subMinutes(12),
            'fecha_inicio_atencion' => $now->copy()->subMinutes(10),
            'fecha_fin_atencion' => null,
        ]);

        ConfirmacionAsistencia::create([
            'turno_id' => $turnoEnAtencion->id,
            'confirmado' => true,
            'fecha_confirmacion' => $now->copy()->subMinutes(25),
            'metodo_confirmacion' => 'sms',
            'token_checkin' => 'CHK-P001-20261126',
            'hora_checkin' => $now->copy()->subMinutes(18),
            'observacion' => 'Validación de prioridad adulto mayor aprobada.',
        ]);

        Notificacion::create([
            'turno_id' => $turnoEnAtencion->id,
            'tipo' => 'llamado_taquilla',
            'canal' => 'sms',
            'mensaje' => 'Turno preferencial P-001: Diríjase al Módulo 1 (T-01).',
            'fecha_envio' => $now->copy()->subMinutes(12),
            'estado_envio' => 'enviado',
        ]);

        RegistroHistorico::create([
            'turno_id' => $turnoEnAtencion->id,
            'usuario_staff_id' => $operador->id,
            'fecha' => $now->copy()->subMinutes(10),
            'accion' => 'inicio_atencion',
            'descripcion' => 'Operador inició atención preferencial para el turno P-001.',
            'resultado' => 'en_curso',
        ]);

        // Turno 3: Llamado
        $turnoLlamado = Turno::create([
            'servicio_id' => $servicioCaja->id,
            'cliente_id' => $cliente3->id,
            'usuario_staff_id' => $moderador->id,
            'taquilla_id' => $taquilla2->id,
            'regla_prioridad_id' => $reglaGeneral->id,
            'codigo_turno' => 'C-001',
            'origen_solicitud' => 'walk_in',
            'puntaje_prioridad' => $reglaGeneral->peso_prioridad,
            'posicion_cola' => 1,
            'estado' => 'llamado',
            'fecha_solicitud' => $now->copy()->subMinutes(20),
            'fecha_llamado' => $now->copy()->subMinutes(2),
            'fecha_inicio_atencion' => null,
            'fecha_fin_atencion' => null,
        ]);

        ConfirmacionAsistencia::create([
            'turno_id' => $turnoLlamado->id,
            'confirmado' => true,
            'fecha_confirmacion' => $now->copy()->subMinutes(20),
            'metodo_confirmacion' => 'kiosco',
            'token_checkin' => 'CHK-C001-20261126',
            'hora_checkin' => $now->copy()->subMinutes(20),
            'observacion' => 'Turno solicitado presencialmente en kiosco.',
        ]);

        Notificacion::create([
            'turno_id' => $turnoLlamado->id,
            'tipo' => 'llamado_taquilla',
            'canal' => 'correo',
            'mensaje' => 'Turno C-001 llamado en Módulo 2 (T-02). Tiene 3 minutos para presentarse.',
            'fecha_envio' => $now->copy()->subMinutes(2),
            'estado_envio' => 'enviado',
        ]);

        RegistroHistorico::create([
            'turno_id' => $turnoLlamado->id,
            'usuario_staff_id' => $moderador->id,
            'fecha' => $now->copy()->subMinutes(2),
            'accion' => 'llamado_turno',
            'descripcion' => 'Turno C-001 llamado hacia Módulo 2.',
            'resultado' => 'pendiente_presentacion',
        ]);

        // Turno 4: En espera
        $turnoEnEspera = Turno::create([
            'servicio_id' => $servicioGeneral->id,
            'cliente_id' => $cliente1->id,
            'usuario_staff_id' => null,
            'taquilla_id' => null,
            'regla_prioridad_id' => $reglaGeneral->id,
            'codigo_turno' => 'A-002',
            'origen_solicitud' => 'remoto',
            'puntaje_prioridad' => $reglaGeneral->peso_prioridad,
            'posicion_cola' => 2,
            'estado' => 'en_espera',
            'fecha_solicitud' => $now->copy()->subMinutes(5),
            'fecha_llamado' => null,
            'fecha_inicio_atencion' => null,
            'fecha_fin_atencion' => null,
        ]);

        ConfirmacionAsistencia::create([
            'turno_id' => $turnoEnEspera->id,
            'confirmado' => true,
            'fecha_confirmacion' => $now->copy()->subMinutes(4),
            'metodo_confirmacion' => 'whatsapp',
            'token_checkin' => 'CHK-A002-20261126',
            'hora_checkin' => null,
            'observacion' => 'Asistencia confirmada, en espera de llamado.',
        ]);

        Notificacion::create([
            'turno_id' => $turnoEnEspera->id,
            'tipo' => 'confirmacion_turno',
            'canal' => 'whatsapp',
            'mensaje' => 'Su turno A-002 en Atención General ha sido confirmado. Posición actual en cola: 2.',
            'fecha_envio' => $now->copy()->subMinutes(4),
            'estado_envio' => 'enviado',
        ]);

        RegistroHistorico::create([
            'turno_id' => $turnoEnEspera->id,
            'usuario_staff_id' => $admin->id,
            'fecha' => $now->copy()->subMinutes(5),
            'accion' => 'creacion_turno',
            'descripcion' => 'Turno A-002 registrado en cola de Atención General.',
            'resultado' => 'en_espera',
        ]);
    }
}
