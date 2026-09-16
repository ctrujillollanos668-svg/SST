<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;

class HallazgoInspeccion extends Model
{
    protected $table = 'hallazgos_inspecciones';
    protected $primaryKey = 'id_hallazgo';

    protected $fillable = [
        'id_inspeccion',
        'descripcion_hallazgo',
        'nivel_riesgo',
        'accion_correctiva',
        'responsable',
        'fecha_compromiso',
        'estado'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionSST::class, 'id_inspeccion', 'id_inspeccion');
    }
}
