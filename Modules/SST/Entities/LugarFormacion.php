<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LugarFormacion extends Model
{
    use HasFactory;

    protected $table = 'lugares_formacion';
    protected $primaryKey = 'id_lugar';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];
}
