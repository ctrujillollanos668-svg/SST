<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PausaActiva extends Model
{
    use HasFactory;

    protected $table = 'pausas_activas';
    protected $primaryKey = 'id_pausa';

    protected $fillable = [
        'id_fase',
        'titulo',
        'descripcion',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'lugar',
        'estado'
    ];

    public function asistencias()
    {
        return $this->hasMany(AsistenciaPausa::class, 'id_pausa', 'id_pausa');
    }
}
