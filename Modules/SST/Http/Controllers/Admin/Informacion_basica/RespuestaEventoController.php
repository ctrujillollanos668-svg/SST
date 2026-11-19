<?php

namespace Modules\SST\Http\Controllers\Admin\Informacion_basica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\RespuestaEvento;

class RespuestaEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $respuestas = RespuestaEvento::orderBy('id_respuesta', 'desc')->get();

        $accidentes = $respuestas->filter(function($r) {
            $data = json_decode($r->descripcion, true);
            $suceso = is_array($data) ? ($data['tipo_suceso'] ?? '') : '';
            return stripos($r->nombre, 'accidente') !== false || stripos($suceso, 'accidente') !== false;
        });

        $incidentes = $respuestas->filter(function($r) {
            $data = json_decode($r->descripcion, true);
            $suceso = is_array($data) ? ($data['tipo_suceso'] ?? '') : '';
            return stripos($r->nombre, 'incidente') !== false || stripos($suceso, 'incidente') !== false;
        });

        $riesgos = $respuestas->filter(function($r) {
            $data = json_decode($r->descripcion, true);
            $suceso = is_array($data) ? ($data['tipo_suceso'] ?? '') : '';
            return stripos($r->nombre, 'riesgo') !== false || stripos($suceso, 'riesgo') !== false;
        });

        $actosInseguros = $respuestas->filter(function($r) {
            $data = json_decode($r->descripcion, true);
            $suceso = is_array($data) ? ($data['tipo_suceso'] ?? '') : '';
            return (stripos($r->nombre, 'acto') !== false && stripos($r->nombre, 'condici') === false)
                || (stripos($suceso, 'acto') !== false && stripos($suceso, 'condici') === false);
        });

        $condicionesInseguras = $respuestas->filter(function($r) {
            $data = json_decode($r->descripcion, true);
            $suceso = is_array($data) ? ($data['tipo_suceso'] ?? '') : '';
            return (stripos($r->nombre, 'condición insegura') !== false || stripos($r->nombre, 'condicion insegura') !== false)
                || (stripos($suceso, 'condición insegura') !== false || stripos($suceso, 'condicion insegura') !== false);
        });

        $lesiones = $respuestas->filter(function($r) {
            $data = json_decode($r->descripcion, true);
            $suceso = is_array($data) ? ($data['tipo_suceso'] ?? '') : '';
            return stripos($r->nombre, 'lesi') !== false || stripos($suceso, 'lesi') !== false;
        });

        $emergencias = $respuestas->filter(function($r) {
            $data = json_decode($r->descripcion, true);
            $suceso = is_array($data) ? ($data['tipo_suceso'] ?? '') : '';
            return stripos($r->nombre, 'emergencia') !== false || stripos($suceso, 'emergencia') !== false;
        });

        return view('sst::admin.informacion_basica.Respuesta_evento', compact(
            'respuestas',
            'accidentes',
            'incidentes',
            'riesgos',
            'actosInseguros',
            'condicionesInseguras',
            'lesiones',
            'emergencias'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'nullable|in:activo,inactivo,Activo,Inactivo',
        ], [
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
        ]);

        $validated['estado'] = strtolower($validated['estado'] ?? 'activo');

        RespuestaEvento::create($validated);

        return redirect()->route('SST.admin.informacion_basica.respuesta_eventos.index')
            ->with('success', 'Tipo de Respuesta registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $respuesta = RespuestaEvento::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u'],
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'nullable|in:activo,inactivo,Activo,Inactivo',
        ], [
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
        ]);

        $validated['estado'] = strtolower($validated['estado'] ?? 'activo');

        $respuesta->update($validated);

        return redirect()->route('SST.admin.informacion_basica.respuesta_eventos.index')
            ->with('success', 'Tipo de Respuesta actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $respuesta = RespuestaEvento::findOrFail($id);
        $respuesta->delete();

        return redirect()->route('SST.admin.informacion_basica.respuesta_eventos.index')
            ->with('success', 'Tipo de Respuesta eliminado correctamente.');
    }

    /**
     * Registrar un nuevo seguimiento/atención del Administrador SST al reporte.
     */
    public function atender(Request $request, $id)
    {
        $respuesta = RespuestaEvento::findOrFail($id);

        $validated = $request->validate([
            'proceso' => ['nullable', 'string', 'max:2000', 'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s.,;:()\-]+$/u'],
            'medidas_tomadas' => 'nullable|string|max:2000',
            'foto_proceso' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'accion' => 'nullable|string|in:guardar,finalizar',
        ], [
            'proceso.regex' => 'El campo Proceso no permite caracteres especiales. Solo se admiten letras, números, espacios y signos de puntuación básicos (. , : ; - ()).',
        ]);

        $procesoTexto = trim($validated['proceso'] ?? $validated['medidas_tomadas'] ?? '');
        if (empty($procesoTexto)) {
            return back()->with('error', 'El campo Proceso es obligatorio.');
        }

        $data = json_decode($respuesta->descripcion, true);
        if (!is_array($data)) {
            $data = [
                'descripcion_original' => $respuesta->descripcion,
            ];
        }

        // Subir foto opcional de evidencia del proceso
        $fotoUrl = null;
        if ($request->hasFile('foto_proceso') && $request->file('foto_proceso')->isValid()) {
            $path = $request->file('foto_proceso')->store('sst_seguimientos', 'public');
            $fotoUrl = asset('storage/' . $path);
        }

        $adminName = 'Administrador SST';
        if (auth()->check()) {
            $adminName = auth()->user()->full_name ?? auth()->user()->name ?? (auth()->user()->nombres ?? 'Administrador SST');
        }

        // Recuperar seguimientos existentes
        $seguimientos = $data['seguimientos'] ?? [];

        // Si existía una respuesta antigua pero aún no estaba en el array de seguimientos, la migramos
        if (empty($seguimientos) && !empty($data['respuesta_admin'])) {
            $seguimientos[] = [
                'id' => 1,
                'fecha' => $data['respuesta_admin']['fecha_atencion'] ?? now()->format('d/m/Y - h:i A'),
                'atendido_por' => $data['respuesta_admin']['atendido_por'] ?? 'Administrador SST',
                'proceso' => $data['respuesta_admin']['medidas_tomadas'] ?? ($data['respuesta_admin']['proceso'] ?? 'Atención inicial'),
                'foto' => $data['respuesta_admin']['foto'] ?? null,
            ];
        }

        $nuevoSeguimiento = [
            'id' => count($seguimientos) + 1,
            'fecha' => now()->format('d/m/Y - h:i A'),
            'atendido_por' => $adminName,
            'proceso' => $procesoTexto,
            'foto' => $fotoUrl,
        ];

        $seguimientos[] = $nuevoSeguimiento;
        $data['seguimientos'] = $seguimientos;

        $esFinalizado = ($request->input('accion') === 'finalizar');

        $data['estado_caso'] = $esFinalizado ? 'atendido' : 'en_proceso';

        // Mantenemos respuesta_admin actualizada con el último estado para compatibilidad
        $data['respuesta_admin'] = [
            'atendido_por' => $adminName,
            'protocolo' => 'Seguimiento SST #' . count($seguimientos),
            'medidas_tomadas' => $procesoTexto,
            'fecha_atencion' => now()->format('d/m/Y - h:i A'),
            'estado' => $esFinalizado ? 'atendido' : 'en_proceso',
            'foto' => $fotoUrl,
        ];

        // Mantenemos el registro activo en BD (la columna estado es enum('activo', 'inactivo'))
        $respuesta->estado = 'activo';
        $respuesta->descripcion = json_encode($data, JSON_UNESCAPED_UNICODE);
        $respuesta->save();

        // Notificar por correo real al Aprendiz sobre la atención / avance del Administrador
        $destinatarioEmail = $data['reportado_por_email'] ?? null;
        $destinatarioNombre = $data['reportado_por_nombre'] ?? ($data['persona_afectada'] ?? 'Aprendiz');

        \Modules\SST\Services\SSTNotificationMailService::enviarNotificacionAprendiz(
            $destinatarioEmail,
            $destinatarioNombre,
            [
                'caso_nombre' => $respuesta->nombre,
                'estado' => $esFinalizado ? 'atendido' : 'en_proceso',
                'es_finalizado' => $esFinalizado,
                'atendido_por' => $adminName,
                'fecha' => now()->format('d/m/Y - h:i A'),
                'medidas_tomadas' => $procesoTexto,
            ]
        );

        $msg = $esFinalizado 
            ? '¡Caso finalizado y resuelto exitosamente!' 
            : '¡Seguimiento #' . count($seguimientos) . ' guardado exitosamente!';

        return redirect()->route('SST.admin.informacion_basica.respuesta_eventos.index')->with('success', $msg);
    }

    /**
     * Finalizar y cerrar directamente un caso SST
     */
    public function finalizar(Request $request, $id)
    {
        $respuesta = RespuestaEvento::findOrFail($id);
        $data = json_decode($respuesta->descripcion, true) ?: [];

        $data['estado_caso'] = 'atendido';
        if (isset($data['respuesta_admin'])) {
            $data['respuesta_admin']['estado'] = 'atendido';
        }
        $respuesta->estado = 'activo';
        $respuesta->descripcion = json_encode($data, JSON_UNESCAPED_UNICODE);
        $respuesta->save();

        // Notificar por correo real al Aprendiz sobre la finalización del caso
        $destinatarioEmail = $data['reportado_por_email'] ?? null;
        $destinatarioNombre = $data['reportado_por_nombre'] ?? ($data['persona_afectada'] ?? 'Aprendiz');

        \Modules\SST\Services\SSTNotificationMailService::enviarNotificacionAprendiz(
            $destinatarioEmail,
            $destinatarioNombre,
            [
                'caso_nombre' => $respuesta->nombre,
                'estado' => 'atendido',
                'es_finalizado' => true,
                'atendido_por' => auth()->check() ? (auth()->user()->full_name ?? auth()->user()->name ?? 'Administrador SST') : 'Administrador SST',
                'fecha' => now()->format('d/m/Y - h:i A'),
                'medidas_tomadas' => 'El caso ha sido solucionado y cerrado formalmente por el Administrador SST.',
            ]
        );

        return redirect()->route('SST.admin.informacion_basica.respuesta_eventos.index')
            ->with('success', '¡El caso ha sido marcado como Finalizado / Resuelto con éxito!');
    }
}
