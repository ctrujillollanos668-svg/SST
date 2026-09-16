<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Modules\SICA\Entities\Person;

class AsistenciaPausa extends Model
{
    protected $table = 'asistencias_pausas';
    protected $primaryKey = 'id_asistencia';

    protected $fillable = [
        'id_pausa',
        'user_id',
        'id_persona',
        'fecha_registro',
        'persona_responsable',
        'estado'
    ];

    public function pausa()
    {
        return $this->belongsTo(PausaActiva::class, 'id_pausa');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'id_persona');
    }
}
