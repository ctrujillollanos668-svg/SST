<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActividadSST extends Model
{
    use HasFactory;

    protected $table = 'actividades_sst';
    protected $primaryKey = 'id_actividad';

    protected $fillable = [
        'tipo_actividad',
        'titulo',
        'descripcion',
        'actividad_a_realizar',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'lugar',
        'instructor',
        'responsable',
        'ficha_programa',
        'participantes',
        'numero_asistentes',
        'observaciones',
        'estado'
    ];
}
