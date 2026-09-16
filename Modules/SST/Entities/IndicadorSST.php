<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;

class IndicadorSST extends Model
{
    protected $table = 'indicadores_sst';
    protected $primaryKey = 'id_indicador';

    protected $fillable = [
        'nombre',
        'tipo',
        'formula',
        'meta',
        'periodicidad',
        'resultado_actual',
        'cumplimiento',
        'estado'
    ];
}
