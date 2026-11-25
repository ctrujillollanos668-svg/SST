<?php

namespace Modules\SST\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LugarFormacionBuilder extends Builder
{
    public function orderBy($column, $direction = 'asc')
    {
        if ($column === 'nombre') $column = 'name';
        if ($column === 'id_lugar') $column = 'id';
        return parent::orderBy($column, $direction);
    }

    public function pluck($column, $key = null)
    {
        if ($column === 'nombre') $column = 'name';
        if ($column === 'id_lugar') $column = 'id';
        if ($key === 'nombre') $key = 'name';
        if ($key === 'id_lugar') $key = 'id';
        return parent::pluck($column, $key);
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if ($column === 'estado') {
            $val = func_num_args() == 2 ? $operator : $value;
            if (is_string($val) && strtolower($val) === 'activo') {
                return parent::where('status', 'Disponible', null, $boolean);
            }
            return $this;
        }
        if ($column === 'nombre') $column = 'name';
        if ($column === 'id_lugar') $column = 'id';
        return parent::where($column, $operator, $value, $boolean);
    }
}

class LugarFormacion extends Model
{
    use HasFactory;

    protected $table = 'environments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'picture',
        'length',
        'latitude',
        'farm_id',
        'status',
        'type_environment',
        'class_environment_id'
    ];

    /**
     * Reemplaza el Builder por defecto para compatibilidad transparente
     */
    public function newEloquentBuilder($query)
    {
        return new LugarFormacionBuilder($query);
    }

    // Compatibilidad con código que use $lugar->nombre
    public function getNombreAttribute()
    {
        return $this->attributes['name'] ?? null;
    }

    // Compatibilidad con código que use $lugar->descripcion
    public function getDescripcionAttribute()
    {
        return $this->attributes['description'] ?? null;
    }

    // Compatibilidad con código que use $lugar->estado
    public function getEstadoAttribute()
    {
        return ($this->attributes['status'] ?? '') === 'Disponible' ? 'activo' : 'inactivo';
    }

    // Compatibilidad con código que use $lugar->id_lugar
    public function getIdLugarAttribute()
    {
        return $this->attributes['id'] ?? null;
    }
}



