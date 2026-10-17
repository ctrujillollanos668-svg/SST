<?php

namespace Modules\SST\Http\Controllers\Admin\Indicadores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;
use Modules\SST\Entities\TipoEvento;
use Modules\SST\Entities\InspeccionSST;
use Modules\SST\Entities\IndicadorSST;

class IndicadoresController extends Controller
{
    /**
     * Muestra el listado de Indicadores de Gestión SST desde la base de datos.
     */
    public function index()
    {
        // Si la tabla está vacía, sembramos los indicadores estándar del Decreto 1072
        if (IndicadorSST::count() === 0) {
            $defaults = [
                [
                    'nombre' => 'Frecuencia de Accidentes (FI)',
                    'tipo' => 'resultado',
                    'formula' => '(N° accidentes en el mes / N° trabajadores) * 100',
                    'meta' => '≤ 1.5%',
                    'periodicidad' => 'Mensual',
                    'resultado_actual' => '0.0%',
                    'cumplimiento' => 'Cumplido',
                    'estado' => 'activo'
                ],
                [
                    'nombre' => 'Severidad de Accidentes (SI)',
                    'tipo' => 'resultado',
                    'formula' => '(N° días de incapacidad / N° trabajadores) * 100',
                    'meta' => '0 días',
                    'periodicidad' => 'Mensual',
                    'resultado_actual' => '0 días',
                    'cumplimiento' => 'Cumplido',
                    'estado' => 'activo'
                ],
                [
                    'nombre' => 'Eficacia de las Inspecciones',
                    'tipo' => 'proceso',
                    'formula' => '(N° hallazgos cerrados / N° hallazgos totales) * 100',
                    'meta' => '≥ 80%',
                    'periodicidad' => 'Trimestral',
                    'resultado_actual' => '82.3%',
                    'cumplimiento' => 'Cumplido',
                    'estado' => 'activo'
                ],
                [
                    'nombre' => 'Cumplimiento de Pausas Activas',
                    'tipo' => 'proceso',
                    'formula' => '(Sesiones realizadas / Sesiones programadas) * 100',
                    'meta' => '≥ 90%',
                    'periodicidad' => 'Mensual',
                    'resultado_actual' => '88.5%',
                    'cumplimiento' => 'En seguimiento',
                    'estado' => 'activo'
                ],
            ];
            foreach ($defaults as $item) {
                IndicadorSST::create($item);
            }
        }

        $indicadores = IndicadorSST::orderBy('id_indicador', 'asc')->get();

        // 1. DATOS PARA GRÁFICA DE ACTIVIDADES SST
        $actividadesList = ActividadSST::all();
        $totalActividades = $actividadesList->count();
        $actividadesRealizadas = $actividadesList->whereIn('estado', ['Realizada', 'Completada'])->count();
        $actividadesProgramadas = $actividadesList->where('estado', 'Programada')->count();
        $actividadesCanceladas = $actividadesList->where('estado', 'Cancelada')->count();

        $actividadesPorTipo = [];
        foreach ($actividadesList as $act) {
            $tipo = $act->tipo_actividad ?: 'Otras';
            $actividadesPorTipo[$tipo] = ($actividadesPorTipo[$tipo] ?? 0) + 1;
        }

        $actividadesStats = [
            'total' => $totalActividades,
            'realizadas' => $actividadesRealizadas,
            'programadas' => $actividadesProgramadas,
            'canceladas' => $actividadesCanceladas,
            'cumplimiento_pct' => $totalActividades > 0 ? round(($actividadesRealizadas / $totalActividades) * 100, 1) : 0,
            'por_tipo' => $actividadesPorTipo,
        ];

        // 2. DATOS PARA GRÁFICA DE TIPOS DE EVENTOS
        $eventosList = TipoEvento::all();
        $totalEventos = $eventosList->count();
        
        $eventosPorCategoria = [];
        $mapNombresCategorias = [
            'accidente' => 'Accidentes de Trabajo',
            'incidente' => 'Incidentes Laborales',
            'tipo_emergencia' => 'Emergencias / Simulacros',
            'acto_inseguro' => 'Actos Inseguros',
            'condicion_insegura' => 'Condiciones Inseguras',
            'lesion' => 'Lesiones',
            'riesgo' => 'Factores de Riesgo',
        ];

        foreach ($eventosList as $ev) {
            $catKey = $ev->tipo_categoria ?: 'otros';
            $catNombre = $mapNombresCategorias[$catKey] ?? ucfirst(str_replace('_', ' ', $catKey));
            $eventosPorCategoria[$catNombre] = ($eventosPorCategoria[$catNombre] ?? 0) + 1;
        }

        $eventosStats = [
            'total' => $totalEventos,
            'por_categoria' => $eventosPorCategoria,
            'activos' => $eventosList->where('estado', 'activo')->count(),
            'inactivos' => $eventosList->where('estado', 'inactivo')->count(),
        ];

        // 3. DATOS PARA GRÁFICA DE INSPECCIONES SST
        $inspeccionesList = InspeccionSST::all();
        $totalInspecciones = $inspeccionesList->count();
        $inspeccionesRealizadas = $inspeccionesList->whereIn('estado', ['realizada', 'Realizada', 'atendida', 'Atendida', 'completada'])->count();
        $inspeccionesPendientes = $inspeccionesList->whereIn('estado', ['pendiente', 'Pendiente', 'en_proceso'])->count();

        $inspeccionesPorTipo = [];
        foreach ($inspeccionesList as $insp) {
            $tipoInsp = $insp->tipo_inspeccion ?: 'General';
            $inspeccionesPorTipo[$tipoInsp] = ($inspeccionesPorTipo[$tipoInsp] ?? 0) + 1;
        }

        $inspeccionesStats = [
            'total' => $totalInspecciones,
            'realizadas' => $inspeccionesRealizadas,
            'pendientes' => $inspeccionesPendientes,
            'efectividad_pct' => $totalInspecciones > 0 ? round(($inspeccionesRealizadas / $totalInspecciones) * 100, 1) : 0,
            'por_tipo' => $inspeccionesPorTipo,
        ];

        return view('sst::admin.indicadores.Ver_indicadores', compact(
            'indicadores',
            'actividadesStats',
            'eventosStats',
            'inspeccionesStats'
        ));
    }

    /**
     * Guarda un nuevo indicador en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'tipo' => 'required|in:estructura,proceso,resultado',
            'formula' => 'nullable|string',
            'meta' => 'nullable|string|max:50',
            'periodicidad' => 'nullable|string|max:50',
            'resultado_actual' => 'nullable|string|max:50',
            'cumplimiento' => 'nullable|string|max:50',
        ]);

        $validated['cumplimiento'] = $validated['cumplimiento'] ?? 'Cumplido';
        $validated['estado'] = 'activo';

        IndicadorSST::create($validated);

        return redirect()->route('SST.admin.indicadores.index')
            ->with('success', 'Indicador registrado con éxito.');
    }

    /**
     * Actualiza un indicador.
     */
    public function update(Request $request, $id)
    {
        $indicador = IndicadorSST::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'tipo' => 'required|in:estructura,proceso,resultado',
            'formula' => 'nullable|string',
            'meta' => 'nullable|string|max:50',
            'periodicidad' => 'nullable|string|max:50',
            'resultado_actual' => 'nullable|string|max:50',
            'cumplimiento' => 'nullable|string|max:50',
        ]);

        $indicador->update($validated);

        return redirect()->route('SST.admin.indicadores.index')
            ->with('success', 'Indicador actualizado con éxito.');
    }

    /**
     * Elimina un indicador.
     */
    public function destroy($id)
    {
        $indicador = IndicadorSST::findOrFail($id);
        $indicador->delete();

        return redirect()->route('SST.admin.indicadores.index')
            ->with('success', 'Indicador eliminado con éxito.');
    }
}
