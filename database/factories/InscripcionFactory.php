<?php

namespace Database\Factories;

use App\Models\Inscripcion;
use App\Models\Alumno;
use Illuminate\Database\Eloquent\Factories\Factory;

class InscripcionFactory extends Factory
{
    protected $model = Inscripcion::class;

    public function definition(): array
    {
        return [
            'alumno_id' => Alumno::factory(),
            'programa_id' => 1,
            'sede_id' => 1,
            'estado' => 'activo',
            'fecha_inscripcion' => now(),
            'fecha_termino' => null,
            'observaciones' => null,
        ];
    }
}