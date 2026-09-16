<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoEvento extends Model
{
    use HasFactory;

    protected $table = 'tipos_eventos';
    protected $primaryKey = 'id_tipo_evento';

    protected $fillable = [
        'tipo_categoria',
        'id_aprendiz',
        'id_fase',
        'nombre',
        'descripcion',
        'modulo_pertenece',
        'notificaciones',
        'estado'
    ];

    protected $casts = [
        'notificaciones' => 'boolean',
    ];
}
