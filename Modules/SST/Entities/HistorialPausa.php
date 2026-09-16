<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;

class HistorialPausa extends Model
{
    protected $table = 'historial_pausas';
    protected $primaryKey = 'id_historial';

    protected $fillable = [
        'id_pausa',
        'id_asistencia',
        'observacion',
        'fecha'
    ];

    public function pausa()
    {
        return $this->belongsTo(PausaActiva::class, 'id_pausa');
    }
}
