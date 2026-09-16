<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CronogramaActividad extends Model
{
    use HasFactory;

    protected $table = 'cronograma_sst';
    protected $primaryKey = 'id_actividad';

    protected $fillable = [
        'id_fase',
        'id_aprendiz',
        'id_administrador',
        'id_lugar',
        'tipo_actividad',
        'nombre',
        'fecha',
        'hora',
        'responsable',
        'estado',
        'descripcion'
    ];

    public function lugar()
    {
        return $this->belongsTo(LugarFormacion::class, 'id_lugar', 'id_lugar');
    }
}
