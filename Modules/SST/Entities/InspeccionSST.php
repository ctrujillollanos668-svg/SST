<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InspeccionSST extends Model
{
    protected $table = 'inspecciones_sst';
    protected $primaryKey = 'id_inspeccion';

    protected $fillable = [
        'lugar_id',
        'user_id',
        'tipo_inspeccion',
        'fecha_inspeccion',
        'hora_inspeccion',
        'estado',
        'observaciones'
    ];

    public function lugar()
    {
        return $this->belongsTo(LugarFormacion::class, 'lugar_id', 'id_lugar');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hallazgos()
    {
        return $this->hasMany(HallazgoInspeccion::class, 'id_inspeccion', 'id_inspeccion');
    }
}
