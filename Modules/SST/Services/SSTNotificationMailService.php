<?php

namespace Modules\SST\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Modules\SST\Entities\TipoEvento;

class SSTNotificationMailService
{
    /**
     * Obtiene los correos reales de los Administradores de SST.
     */
    public static function getAdminEmails(): array
    {
        $emails = [];

        try {
            // Buscar usuarios con roles de administrador SST o superadmin
            $adminUsers = User::whereHas('roles', function ($q) {
                $q->whereIn('slug', ['sst.admin', 'superadmin', 'admin']);
            })->pluck('email')->filter()->toArray();

            if (!empty($adminUsers)) {
                $emails = array_merge($emails, $adminUsers);
            }
        } catch (\Throwable $e) {
            // Ignorar si hay problema de relación en BD
        }

        // Si no se encontraron usuarios específicos, usar el correo del usuario actual (si es admin) o el configurado en mail.php
        if (empty($emails)) {
            $defaultFrom = config('mail.from.address');
            if (!empty($defaultFrom) && filter_var($defaultFrom, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $defaultFrom;
            } else {
                $emails[] = 'admin.sst@sena.edu.co';
            }
        }

        return array_unique($emails);
    }

    /**
     * Verifica si un Tipo de Evento tiene habilitado el atributo 'notificaciones' (booleano).
     */
    public static function debeNotificarEvento(?string $nombreTipo, ?string $categoria = null): bool
    {
        try {
            $query = TipoEvento::query();
            if ($nombreTipo) {
                $query->where('nombre', $nombreTipo);
            }
            if ($categoria) {
                $query->orWhere('tipo_categoria', $categoria);
            }
            $tipo = $query->first();

            // Si existe en el catálogo, respetamos el atributo 'notificaciones' (true/false)
            if ($tipo) {
                return (bool) $tipo->notificaciones;
            }
        } catch (\Throwable $e) {
            // Si hay error en consulta, por defecto permitimos enviar si es un reporte formal
        }

        // Por defecto, si no está en catálogo o no tiene restricción, se notifica
        return true;
    }

    /**
     * Envía un correo electrónico real al Administrador SST cuando un aprendiz reporta un evento.
     */
    public static function enviarNotificacionAdmin(array $datos): void
    {
        // Validar el atributo de notificaciones
        $tipoNombre = $datos['tipo_evento'] ?? null;
        $categoria = $datos['categoria'] ?? null;
        
        if (!self::debeNotificarEvento($tipoNombre, $categoria)) {
            Log::info("SST Mail: Notificación omitida porque el atributo 'notificaciones' está desactivado para {$tipoNombre}.");
            return;
        }

        $adminEmails = self::getAdminEmails();
        if (empty($adminEmails)) return;

        $asunto = '⚠️ SG-SST: Nuevo Reporte de ' . ($datos['tipo_evento'] ?? 'Evento') . ' - SENA Empresa';

        $dataView = [
            'asunto' => $asunto,
            'titulo' => 'Nuevo Reporte de ' . ($datos['tipo_evento'] ?? 'Evento de Seguridad'),
            'tipoEvento' => $datos['tipo_evento'] ?? 'Evento de Seguridad',
            'afectado' => $datos['afectado'] ?? 'Personal Aprendiz',
            'lugar' => $datos['lugar'] ?? 'Instalaciones del Centro',
            'fecha' => $datos['fecha'] ?? now()->format('d/m/Y - h:i A'),
            'gravedad' => $datos['gravedad'] ?? 'Leve',
            'descripcion' => $datos['descripcion'] ?? 'Sin observaciones.',
            'enlaceAccion' => route('SST.admin.informacion_basica.respuesta_eventos.index'),
        ];

        try {
            foreach ($adminEmails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Mail::send('sst::emails.notificacion_admin', $dataView, function ($message) use ($email, $asunto) {
                        $message->to($email)
                                ->subject($asunto);
                    });
                }
            }
            Log::info("SST Mail: Notificación enviada con éxito a Administrador(es): " . implode(', ', $adminEmails));
        } catch (\Throwable $e) {
            Log::warning("SST Mail Error al enviar correo real al Admin: " . $e->getMessage());
        }
    }

    /**
     * Envía un correo electrónico real al Aprendiz cuando el Administrador da seguimiento o finaliza su caso.
     */
    public static function enviarNotificacionAprendiz(?string $destinatarioEmail, ?string $destinatarioNombre, array $datos): void
    {
        $email = $destinatarioEmail;
        if (empty($email) && auth()->check()) {
            $email = auth()->user()->email ?? (auth()->user()->person->email ?? null);
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::info("SST Mail: No se pudo enviar correo al Aprendiz porque no tiene un correo válido asignado.");
            return;
        }

        $esFinalizado = ($datos['estado'] ?? '') === 'atendido' || ($datos['es_finalizado'] ?? false);
        $asunto = $esFinalizado
            ? '✅ SG-SST: Tu caso de seguridad ha sido RESUELTO y finalizado'
            : '📋 SG-SST: Novedad y seguimiento a tu caso de seguridad';

        $dataView = [
            'asunto' => $asunto,
            'titulo' => $esFinalizado ? '¡Tu caso ha sido resuelto y finalizado!' : 'Nuevo avance y seguimiento en tu reporte',
            'destinatarioNombre' => $destinatarioNombre ?? 'Aprendiz',
            'asuntoCaso' => $datos['caso_nombre'] ?? 'Reporte de Seguridad SST',
            'estado' => $esFinalizado ? 'Atendido y Resuelto' : 'En Proceso de Seguimiento',
            'atendidoPor' => $datos['atendido_por'] ?? 'Administrador SST',
            'fechaAtencion' => $datos['fecha'] ?? now()->format('d/m/Y - h:i A'),
            'medidasTomadas' => $datos['medidas_tomadas'] ?? 'Seguimiento registrado por el área de SST.',
            'enlaceAccion' => route('SST.aprendiz.dashboard'),
        ];

        try {
            Mail::send('sst::emails.notificacion_aprendiz', $dataView, function ($message) use ($email, $asunto) {
                $message->to($email)
                        ->subject($asunto);
            });
            Log::info("SST Mail: Notificación enviada con éxito al Aprendiz: {$email}");
        } catch (\Throwable $e) {
            Log::warning("SST Mail Error al enviar correo real al Aprendiz: " . $e->getMessage());
        }
    }
}

