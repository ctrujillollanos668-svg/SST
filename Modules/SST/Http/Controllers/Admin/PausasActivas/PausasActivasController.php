<?php

namespace Modules\SST\Http\Controllers\Admin\PausasActivas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\PausaActiva;
use Modules\SST\Entities\AsistenciaPausa;
use Modules\SST\Entities\LugarFormacion;
use App\Models\User;

class PausasActivasController extends Controller
{
    /**
     * Muestra el listado de Pausas Activas Programadas desde la base de datos.
     */
    public function index()
    {
        if (PausaActiva::count() === 0) {
            PausaActiva::create([
                'titulo' => 'Estiramiento Osteomuscular',
                'descripcion' => 'Ejercicios de estiramiento y relajación muscular para extremidades y cuello.',
                'fecha' => now()->toDateString(),
                'hora_inicio' => '10:00:00',
                'hora_fin' => '10:15:00',
                'lugar' => 'Área Administrativa y Docentes',
                'estado' => 'activo'
            ]);

            PausaActiva::create([
                'titulo' => 'Gimnasia Visual y Ocular',
                'descripcion' => 'Descanso visual y ejercicios de parpadeo y enfoque para personal de cómputo.',
                'fecha' => now()->toDateString(),
                'hora_inicio' => '15:30:00',
                'hora_fin' => '15:40:00',
                'lugar' => 'Salas de Cómputo e Informática',
                'estado' => 'activo'
            ]);
        }

        $pausas = PausaActiva::with('asistencias')->orderBy('id_pausa', 'desc')->paginate(15);
        $lugares = LugarFormacion::orderBy('nombre', 'asc')->get();
        $usuarios = User::with('person')->orderBy('nickname', 'asc')->get();

        return view('sst::admin.pausas activa.Programas', compact('pausas', 'lugares', 'usuarios'));
    }

    /**
     * Guarda una nueva pausa activa programada.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'nullable',
            'lugar' => 'nullable|string|max:150',
            'estado' => 'nullable|in:activo,inactivo'
        ]);

        $validated['estado'] = $validated['estado'] ?? 'activo';

        PausaActiva::create($validated);

        return redirect()->route('SST.admin.pausas_activas.index')
            ->with('success', 'Pausa activa programada exitosamente.');
    }

    /**
     * Registra la asistencia a una pausa activa.
     */
    public function storeAsistencia(Request $request)
    {
        $validated = $request->validate([
            'id_pausa' => 'required|exists:pausas_activas,id_pausa',
            'user_id' => 'required|exists:users,id',
            'persona_responsable' => 'nullable|string|max:100',
            'estado' => 'nullable|in:registrado,no_registrado,justificado'
        ]);

        $validated['fecha_registro'] = now();
        $validated['estado'] = $validated['estado'] ?? 'registrado';

        AsistenciaPausa::create($validated);

        return redirect()->route('SST.admin.pausas_activas.index')
            ->with('success', 'Asistencia registrada correctamente en la base de datos.');
    }
}
