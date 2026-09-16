<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactoEmergencia extends Model
{
    use HasFactory;

    protected $table = 'contactos_emergencia';
    protected $primaryKey = 'id_contacto';

    protected $fillable = [
        'id_aprendiz',
        'nombre',
        'telefono',
        'descripcion',
        'estado'
    ];
}
