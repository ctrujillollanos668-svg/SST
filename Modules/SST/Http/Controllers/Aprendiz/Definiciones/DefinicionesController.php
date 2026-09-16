<?php

namespace Modules\SST\Http\Controllers\Aprendiz\Definiciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\SST\Entities\TipoEvento;

class DefinicionesController extends Controller
{
    /**
     * Vista de Definiciones y Glosario para el Aprendiz SST
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('SST.aprendiz.definiciones')]);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->roles->contains('slug', 'sst.aprendiz') || $user->roles->contains('slug', 'sst.funcionario') || $user->roles->contains('slug', 'sst.admin') || $user->hasSuperAdmin()) {
            
            // Consultar definiciones agrupadas por categoría
            $eventos = TipoEvento::where('estado', 'activo')
                ->orderBy('nombre', 'asc')
                ->get();

            $categorias = [
                'accidentes' => [
                    'titulo' => 'Accidentes',
                    'categoria_slug' => 'accidente',
                    'icono' => 'fa-solid fa-burst',
                    'color_accent' => 'orange',
                    'bg_light' => 'bg-orange-50/60',
                    'border_color' => 'border-orange-200/80',
                    'text_color' => 'text-orange-600',
                    'badge_bg' => 'bg-orange-100/80',
                    'badge_text' => 'text-orange-700',
                    'items' => $eventos->where('tipo_categoria', 'accidente')
                ],
                'incidentes' => [
                    'titulo' => 'Incidentes',
                    'categoria_slug' => 'incidente',
                    'icono' => 'fa-solid fa-triangle-exclamation',
                    'color_accent' => 'amber',
                    'bg_light' => 'bg-amber-50/60',
                    'border_color' => 'border-amber-200/80',
                    'text_color' => 'text-amber-600',
                    'badge_bg' => 'bg-amber-100/80',
                    'badge_text' => 'text-amber-700',
                    'items' => $eventos->where('tipo_categoria', 'incidente')
                ],
                'riesgos' => [
                    'titulo' => 'Riesgos',
                    'categoria_slug' => 'riesgo',
                    'icono' => 'fa-solid fa-biohazard',
                    'color_accent' => 'blue',
                    'bg_light' => 'bg-blue-50/60',
                    'border_color' => 'border-blue-200/80',
                    'text_color' => 'text-blue-600',
                    'badge_bg' => 'bg-blue-100/80',
                    'badge_text' => 'text-blue-700',
                    'items' => $eventos->where('tipo_categoria', 'riesgo')
                ],
                'actos_inseguros' => [
                    'titulo' => 'Actos Inseguros',
                    'categoria_slug' => 'acto_inseguro',
                    'icono' => 'fa-solid fa-lightbulb',
                    'color_accent' => 'yellow',
                    'bg_light' => 'bg-yellow-50/60',
                    'border_color' => 'border-yellow-200/80',
                    'text_color' => 'text-yellow-600',
                    'badge_bg' => 'bg-yellow-100/80',
                    'badge_text' => 'text-yellow-700',
                    'items' => $eventos->where('tipo_categoria', 'acto_inseguro')
                ],
                'lesiones' => [
                    'titulo' => 'Lesiones',
                    'categoria_slug' => 'lesion',
                    'icono' => 'fa-solid fa-crutch',
                    'color_accent' => 'purple',
                    'bg_light' => 'bg-purple-50/60',
                    'border_color' => 'border-purple-200/80',
                    'text_color' => 'text-purple-600',
                    'badge_bg' => 'bg-purple-100/80',
                    'badge_text' => 'text-purple-700',
                    'items' => $eventos->where('tipo_categoria', 'lesion')
                ],
                'tipos_emergencias' => [
                    'titulo' => 'Tipos de Emergencias',
                    'categoria_slug' => 'tipo_emergencia',
                    'icono' => 'fa-solid fa-hospital',
                    'color_accent' => 'rose',
                    'bg_light' => 'bg-rose-50/60',
                    'border_color' => 'border-rose-200/80',
                    'text_color' => 'text-rose-600',
                    'badge_bg' => 'bg-rose-100/80',
                    'badge_text' => 'text-rose-700',
                    'items' => $eventos->where('tipo_categoria', 'tipo_emergencia')
                ],
            ];

            return view('sst::aprendiz.definiciones.Definiciones', compact('categorias'));
        }

        abort(403, 'Acceso denegado: No tienes permisos para consultar este recurso en el módulo SST.');
    }
}
