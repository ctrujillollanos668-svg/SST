<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RespuestaEvento extends Model
{
    use HasFactory;

    protected $table = 'respuesta_eventos';
    protected $primaryKey = 'id_respuesta';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];
}
