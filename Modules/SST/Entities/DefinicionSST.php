<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DefinicionSST extends Model
{
    use HasFactory;

    protected $table = 'definiciones_sst';
    protected $primaryKey = 'id_definicion';

    protected $fillable = [
        'tipo_categoria',
        'nombre',
        'descripcion',
        'como_prevenir',
        'tipo_gravedad',
        'tipo_clasificacion',
        'tipo_otro',
        'modulo_pertenece',
        'estado'
    ];
}
