<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $asunto ?? 'Notificación SG-SST' }}</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; color: #1e293b;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        
        <!-- Header Institucional SENA -->
        <tr>
            <td style="background: linear-gradient(135deg, #0A2333 0%, #0F3247 100%); padding: 30px 25px; text-align: center; border-bottom: 4px solid #39A900;">
                <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.5px;">
                    SISTEMA DE GESTIÓN SST
                </h1>
                <p style="color: #62E31D; margin: 6px 0 0 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                    SENA Empresa • La Angostura
                </p>
            </td>
        </tr>

        <!-- Contenido Principal -->
        <tr>
            <td style="padding: 35px 30px;">
                <div style="display: inline-block; padding: 6px 14px; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 30px; color: #dc2626; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 15px;">
                    ⚠️ Alerta de Seguridad SST
                </div>

                <h2 style="color: #0f172a; margin: 0 0 15px 0; font-size: 18px; font-weight: 800;">
                    {{ $titulo ?? 'Nuevo Evento Reportado' }}
                </h2>

                <p style="color: #475569; font-size: 13px; line-height: 1.6; margin: 0 0 25px 0;">
                    Estimado(a) <strong>Administrador(a) SST</strong>, se le notifica que se ha registrado un nuevo reporte en el Sistema de Gestión de Seguridad y Salud en el Trabajo.
                </p>

                <!-- Tarjeta de Detalles del Evento -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; margin-bottom: 25px;">
                    @if(!empty($tipoEvento))
                    <tr>
                        <td style="padding: 6px 0; font-size: 12px; color: #64748b; font-weight: bold; width: 35%;">Tipo de Evento:</td>
                        <td style="padding: 6px 0; font-size: 12px; color: #0f172a; font-weight: 800;">{{ $tipoEvento }}</td>
                    </tr>
                    @endif
                    @if(!empty($afectado))
                    <tr>
                        <td style="padding: 6px 0; font-size: 12px; color: #64748b; font-weight: bold;">Persona / Afectado:</td>
                        <td style="padding: 6px 0; font-size: 12px; color: #0f172a; font-weight: bold;">{{ $afectado }}</td>
                    </tr>
                    @endif
                    @if(!empty($lugar))
                    <tr>
                        <td style="padding: 6px 0; font-size: 12px; color: #64748b; font-weight: bold;">Lugar / Ambiente:</td>
                        <td style="padding: 6px 0; font-size: 12px; color: #0f172a; font-weight: bold;">{{ $lugar }}</td>
                    </tr>
                    @endif
                    @if(!empty($fecha))
                    <tr>
                        <td style="padding: 6px 0; font-size: 12px; color: #64748b; font-weight: bold;">Fecha del Suceso:</td>
                        <td style="padding: 6px 0; font-size: 12px; color: #0f172a; font-weight: bold;">{{ $fecha }}</td>
                    </tr>
                    @endif
                    @if(!empty($gravedad))
                    <tr>
                        <td style="padding: 6px 0; font-size: 12px; color: #64748b; font-weight: bold;">Nivel de Gravedad:</td>
                        <td style="padding: 6px 0; font-size: 12px; color: #dc2626; font-weight: 800;">{{ $gravedad }}</td>
                    </tr>
                    @endif
                    @if(!empty($descripcion))
                    <tr>
                        <td colspan="2" style="padding-top: 10px; border-top: 1px dashed #cbd5e1; margin-top: 8px;">
                            <span style="font-size: 11px; color: #64748b; font-weight: bold; display: block; margin-bottom: 4px;">Descripción reportada:</span>
                            <span style="font-size: 12px; color: #334155; line-height: 1.5; font-style: italic;">"{{ $descripcion }}"</span>
                        </td>
                    </tr>
                    @endif
                </table>

                <p style="color: #64748b; font-size: 12px; margin: 0 0 25px 0;">
                    Por favor ingrese al panel administrativo para verificar la evidencia fotográfica, aplicar el protocolo correspondiente y registrar los seguimientos.
                </p>

                <!-- Botón CTA -->
                <div style="text-align: center;">
                    <a href="{{ $enlaceAccion ?? url('/Sst/admin/informacion-basica/respuesta-eventos') }}" 
                       style="display: inline-block; background-color: #39A900; color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 800; padding: 12px 28px; border-radius: 12px; box-shadow: 0 4px 10px rgba(57, 169, 0, 0.3);">
                        Gestionar Caso en ERP SST &rarr;
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 11px; color: #94a3b8;">
                Mensaje generado automáticamente por el Sistema de Gestión de Seguridad y Salud en el Trabajo (SG-SST).<br>
                SENA Centro de Formación Agroindustrial La Angostura.
            </td>
        </tr>

    </table>
</body>
</html>

