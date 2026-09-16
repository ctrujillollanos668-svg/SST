<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoActividad extends Model
{
    use HasFactory;

    protected $table = 'tipos_actividades_sst';
    protected $primaryKey = 'id_tipo_actividad';

    protected $fillable = [
        'nombre',
        'icono',
        'color',
        'estado'
    ];
}
