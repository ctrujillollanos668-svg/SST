<?php

namespace Modules\SST\Http\Controllers\Admin\Inspecciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InspeccionesController extends Controller
{
    /**
     * Muestra el formulario interactivo para realizar una nueva inspección SST.
     */
    public function realizar()
    {
        return view('sst::admin.inspecciones.Realizar_inspeccion');
    }

    /**
     * Muestra el historial y listado de inspecciones de seguridad registradas.
     */
    public function historial()
    {
        return view('sst::admin.inspecciones.Historial');
    }
}
