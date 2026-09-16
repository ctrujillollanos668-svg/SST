<?php

namespace Modules\Direccion\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Direccion\Entities\Politica;
use Modules\SICA\Entities\Bloque;
use Modules\SICA\Entities\ProductiveUnit;

class DireccionController extends Controller
{
    /**
     * Validar autorización:
     * - Si no hay nadie logueado (invitado), puede ver la landing de Dirección si allowGuest es true.
     * - Si está logueado damendez o tiene rol de Dirección / SuperAdmin, tiene acceso.
     * - Si está logueado otro usuario sin permisos, cierra sesión y redirige al login con error.
     */
    private function authorizeDireccion($allowGuest = false)
    {
        if (!auth()->check()) {
            if ($allowGuest) {
                return null;
            }
            return redirect()->route('login', ['redirect' => route('direccion.dashboard')]);
        }

        $user = auth()->user();
        $isDamendez = strtolower($user->nickname ?? '') === 'damendez' || strtolower($user->email ?? '') === 'ing.diego.mendez@gmail.com';
        $hasDireccionRole = $user->hasRole('direccion.admin') || $user->hasSuperAdmin();

        if (!$isDamendez && !$hasDireccionRole) {
            abort(404);
        }

        return null;
    }

    /**
     * Página de Bienvenida / Landing Institucional del Módulo de Dirección.
     */
    public function welcome()
    {
        if ($redirect = $this->authorizeDireccion(true)) {
            return $redirect;
        }

        $totalPoliticas = Politica::count();
        $activas = Politica::where('estado', 'Activa')->count();
        $enRevision = Politica::where('estado', 'En Revisión')->count();
        $inactivas = Politica::where('estado', 'Inactiva')->count();

        // Políticas destacadas para el landing
        $politicasDestacadas = Politica::orderBy('created_at', 'desc')->take(4)->get();

        return view('direccion::welcome', compact(
            'totalPoliticas',
            'activas',
            'enRevision',
            'inactivas',
            'politicasDestacadas'
        ));
    }

    /**
     * Dashboard / Tablero de Control Ejecutivo de Dirección.
     */
    public function dashboard()
    {
        if ($redirect = $this->authorizeDireccion()) {
            return $redirect;
        }

        $totalPoliticas = Politica::count();
        $activas = Politica::where('estado', 'Activa')->count();
        $enRevision = Politica::where('estado', 'En Revisión')->count();
        $inactivas = Politica::where('estado', 'Inactiva')->count();

        // Desglose por tipo
        $porTipo = [
            'Estratégica' => Politica::where('tipo', 'Estratégica')->count(),
            'Calidad' => Politica::where('tipo', 'Calidad')->count(),
            'Seguridad' => Politica::where('tipo', 'Seguridad')->count(),
            'Operativa' => Politica::where('tipo', 'Operativa')->count(),
        ];

        // Últimas políticas creadas
        $ultimasPoliticas = Politica::orderBy('created_at', 'desc')->take(5)->get();

        return view('direccion::dashboard', compact(
            'totalPoliticas',
            'activas',
            'enRevision',
            'inactivas',
            'porTipo',
            'ultimasPoliticas'
        ));
    }
}
