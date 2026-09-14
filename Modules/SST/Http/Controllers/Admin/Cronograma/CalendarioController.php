<?php

namespace Modules\SST\Http\Controllers\Admin\Cronograma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    /**
     * Display the calendar and activities schedule.
     */
    public function index()
    {
        return view('sst::admin.cronograma.Calendario');
    }
}
