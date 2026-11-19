<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $asunto ?? 'Actualización SG-SST' }}</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; color: #1e293b;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        
        <!-- Header Institucional SENA -->
        <tr>
            <td style="background: linear-gradient(135deg, #0A2333 0%, #0F3247 100%); padding: 30px 25px; text-align: center; border-bottom: 4px solid #39A900;">
                <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.5px;">
                    PORTAL APRENDIZ • SG-SST
                </h1>
                <p style="color: #62E31D; margin: 6px 0 0 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                    Ambiente Aprendiz Seguro • SENA Empresa
                </p>
            </td>
        </tr>

        <!-- Contenido Principal -->
        <tr>
            <td style="padding: 35px 30px;">
                <div style="display: inline-block; padding: 6px 14px; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 30px; color: #047857; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 15px;">
                    ✅ Actualización de Seguridad SST
                </div>

                <h2 style="color: #0f172a; margin: 0 0 15px 0; font-size: 18px; font-weight: 800;">
                    {{ $titulo ?? 'Actualización en tu reporte de SST' }}
                </h2>

                <p style="color: #475569; font-size: 13px; line-height: 1.6; margin: 0 0 25px 0;">
                    Hola <strong>{{ $destinatarioNombre ?? 'Aprendiz' }}</strong>, te informamos que el <strong>Administrador SST</strong> ha registrado novedades respecto a tus actividades o reportes de seguridad.
                </p>

                <!-- Tarjeta de Detalles del Avance -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; margin-bottom: 25px;">
                    @if(!empty($asuntoCaso))
                    <tr>
                        <td style="padding: 6px 0; font-size: 12px; color: #64748b; font-weight: bold; width: 35%;">Referencia / Caso:</td>
                        <td style="padding: 6px 0; font-size: 12px; color: #0f172a; font-weight: 800;">{{ $asuntoCaso }}</td>
                    </tr>
                    @endif
                    @if(!empty($estado))
                    <tr>
                        <td style="padding: 6px 0; font-size: 12px; color: #64748b; font-weight: bold;">Estado del Caso:</td>
                        <td style="padding: 6px 0; font-size: 12px; color: #047857; font-weight: 800; text-transform: uppercase;">{{ $estado }}</td>
                    </tr>
                    @endif
                    @if(!empty($atendidoPor))
                    <tr>
                        <td style="padding: 6px 0; font-size: 12px; color: #64748b; font-weight: bold;">Atendido Por:</td>
                        <td style="padding: 6px 0; font-size: 12px; color: #0f172a; font-weight: bold;">{{ $atendidoPor }}</td>
                    </tr>
                    @endif
                    @if(!empty($fechaAtencion))
                    <tr>
                        <td style="padding: 6px 0; font-size: 12px; color: #64748b; font-weight: bold;">Fecha de Gestión:</td>
                        <td style="padding: 6px 0; font-size: 12px; color: #0f172a; font-weight: bold;">{{ $fechaAtencion }}</td>
                    </tr>
                    @endif
                    @if(!empty($medidasTomadas))
                    <tr>
                        <td colspan="2" style="padding-top: 10px; border-top: 1px dashed #cbd5e1; margin-top: 8px;">
                            <span style="font-size: 11px; color: #64748b; font-weight: bold; display: block; margin-bottom: 4px;">Medidas tomadas y seguimiento:</span>
                            <span style="font-size: 12px; color: #334155; line-height: 1.5;">{{ $medidasTomadas }}</span>
                        </td>
                    </tr>
                    @endif
                </table>

                <p style="color: #64748b; font-size: 12px; margin: 0 0 25px 0;">
                    Puedes ingresar en cualquier momento a la plataforma para consultar los detalles y mantenerte al día con las medidas de prevención.
                </p>

                <!-- Botón CTA -->
                <div style="text-align: center;">
                    <a href="{{ $enlaceAccion ?? url('/Sst/aprendiz/dashboard') }}" 
                       style="display: inline-block; background-color: #39A900; color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 800; padding: 12px 28px; border-radius: 12px; box-shadow: 0 4px 10px rgba(57, 169, 0, 0.3);">
                        Ver en el Portal del Aprendiz &rarr;
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 11px; color: #94a3b8;">
                Sistema de Gestión de Seguridad y Salud en el Trabajo (SG-SST) • SENA Empresa La Angostura.<br>
                Este es un mensaje institucional generado automáticamente.
            </td>
        </tr>

    </table>
</body>
</html>

