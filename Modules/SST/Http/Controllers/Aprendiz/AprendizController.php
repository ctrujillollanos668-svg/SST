<?php

namespace Modules\SST\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\SST\Entities\ActividadSST;
use Modules\SST\Entities\TipoEvento;
use Modules\SST\Entities\InspeccionSST;
use Modules\SST\Entities\HallazgoInspeccion;

class AprendizController extends Controller
{
    /**
     * Dashboard / Panel del Aprendiz SST con Indicadores y Gráficas de Gestión
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.aprendiz.dashboard')]);
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user->roles->contains('slug', 'sst.aprendiz') && !$user->roles->contains('slug', 'sst.funcionario') && !$user->roles->contains('slug', 'sst.admin') && !$user->hasSuperAdmin()) {
            abort(403, 'Acceso denegado: No tienes permisos de Aprendiz en el módulo SST.');
        }

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

        $totalHallazgos = HallazgoInspeccion::count();
        $hallazgosCerrados = HallazgoInspeccion::where('estado', 'cerrado')->count();
        $hallazgosAbiertos = HallazgoInspeccion::whereIn('estado', ['abierto', 'en_proceso'])->count();

        $inspeccionesStats = [
            'total' => $totalInspecciones,
            'realizadas' => $inspeccionesRealizadas,
            'pendientes' => $inspeccionesPendientes,
            'efectividad_pct' => $totalInspecciones > 0 ? round(($inspeccionesRealizadas / $totalInspecciones) * 100, 1) : 0,
            'por_tipo' => $inspeccionesPorTipo,
            'total_hallazgos' => $totalHallazgos,
            'hallazgos_cerrados' => $hallazgosCerrados,
            'hallazgos_abiertos' => $hallazgosAbiertos,
            'eficacia_hallazgos_pct' => $totalHallazgos > 0 ? round(($hallazgosCerrados / $totalHallazgos) * 100, 1) : 100,
        ];

        return view('sst::aprendiz.dashboard', compact(
            'actividadesStats',
            'eventosStats',
            'inspeccionesStats'
        ));
    }
}
