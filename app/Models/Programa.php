<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas';
    protected $fillable = ['nombre', 'objetivo', 'inscripcion', 'precio_materia', 'precio_materia_reprobada', 'plazos_colegiatura', 'minima', 'limite_pago', 'modalidad_id', 'rvoe_id', 'tags'];

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'programa_materia');
    }



// falta relacion inversa

    public function inscripciones()
{
    return $this->hasMany(Inscripcion::class, 'programa_id');
}

public function scopeValidos($query)
{
    return $query
        ->whereNotNull('nombre')
        ->whereRaw("TRIM(nombre) <> ''")
        ->where('precio_materia', '>', 0);
}
}
