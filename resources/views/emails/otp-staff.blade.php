<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de verificación FLUXXO</title>
</head>
<body style="margin:0;padding:0;background-color:#F9FAFB;font-family:Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:520px;background-color:#FFFFFF;border:1px solid #E5E7EB;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="padding:24px 32px;border-bottom:1px solid #E5E7EB;background-color:#111827;">
                            <span style="font-size:20px;font-weight:700;letter-spacing:0.06em;color:#FFFFFF;">FLUXXO</span>
                            <span style="display:inline-block;margin-left:8px;padding:2px 8px;font-size:11px;font-weight:600;border-radius:9999px;background-color:#7C3AED;color:#FFFFFF;">PORTAL STAFF</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 12px;font-size:15px;color:#374151;">
                                Hola, <strong style="color:#111827;">{{ $staff->nombre }}</strong>:
                            </p>
                            <p style="margin:0 0 24px;font-size:14px;line-height:1.6;color:#4B5563;">
                                Recibimos una solicitud de inicio de sesión para tu cuenta institucional en <strong>FLUXXO</strong>. Usa el siguiente código de verificación de un solo uso para completar tu acceso:
                            </p>

                            <div style="text-align:center;margin:24px 0;padding:20px;background-color:#F5F3FF;border:1px solid #DDD6FE;border-radius:10px;">
                                <span style="display:block;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#7C3AED;margin-bottom:8px;">
                                    Código de verificación
                                </span>
                                <span style="font-family:'Courier New', Courier, monospace;font-size:34px;font-weight:700;letter-spacing:0.28em;color:#111827;">
                                    {{ $codigo }}
                                </span>
                            </div>

                            <p style="margin:0 0 8px;font-size:13px;color:#4B5563;">
                                Este código tiene una vigencia de <strong>{{ $vigenciaMinutos }} minutos</strong> y se invalida automáticamente una vez utilizado.
                            </p>
                            <p style="margin:0;font-size:12px;color:#6B7280;">
                                Si no solicitaste este acceso, ignora este mensaje y notifica al administrador de tu organización.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px;background-color:#F9FAFB;border-top:1px solid #E5E7EB;text-align:center;font-size:12px;color:#6B7280;">
                            &copy; {{ date('Y') }} Fluxxo Turnos &mdash; Gestión Inteligente de Filas y Atención
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
