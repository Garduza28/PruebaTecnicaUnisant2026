<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $fillable = [
        'matricula', 'nombre_completo', 'apat', 'amat', 'curp',
        'email', 'telefono', 'sede_id', 'organizacion_id',
        'fecha_nacimiento', 'fecha_inscripcion', 'estado', 'tags'
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'alumno_id');
    }


    public function adeudos()
{
    return $this->hasMany(Adeudo::class, 'alumno_id');
}

    public function scopePorMatricula($query, $matricula)
    {
        return $query->where('matricula', $matricula);
    }


    public function scopeValidos($query)
    {
        return $query
            ->whereNotNull('matricula')
            ->where('matricula', '!=', '')
            ->whereNotNull('nombre_completo')
            ->where('nombre_completo', '!=', '');
    }

    public function scopeBusqueda($query, $texto)
{
    return $query->validos()
        ->whereIn('id', function ($sub) {
            $sub->selectRaw('MIN(id)')
                ->from('alumnos')
                ->groupBy('matricula');
        })
        ->where(function ($q) use ($texto) {
            $q->where('matricula', 'like', "%{$texto}%")
              ->orWhere('nombre_completo', 'like', "%{$texto}%");
        });
}
    
}