<?php

namespace Modules\SST\Http\Controllers\Aprendiz\ActividadesSST;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SST\Entities\ActividadSST;

class SensibilizacionController extends Controller
{
    /**
     * Muestra el listado de Jornadas de Sensibilización para el Aprendiz.
     */
    public function index(Request $request)
    {
        $query = ActividadSST::where('tipo_actividad', 'Sensibilización');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('lugar', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%")
                  ->orWhere('ficha_programa', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        }

        $actividades = $query->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'asc')->paginate(12)->withQueryString();

        $stats = [
            'total' => ActividadSST::where('tipo_actividad', 'Sensibilización')->count(),
            'programadas' => ActividadSST::where('tipo_actividad', 'Sensibilización')->where('estado', 'Programada')->count(),
            'realizadas' => ActividadSST::where('tipo_actividad', 'Sensibilización')->where('estado', 'Realizada')->count(),
        ];

        return view('sst::aprendiz.actividades_sst.Sensibilizacion', compact('actividades', 'stats'));
    }
}
