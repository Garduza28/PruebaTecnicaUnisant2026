<?php

namespace Database\Factories;

use App\Models\Alumno;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlumnoFactory extends Factory
{
    protected $model = Alumno::class;

    public function definition(): array
    {
        return [
            'matricula' => $this->faker->unique()->numerify('A###'),
            'nombre_completo' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'apat' => $this->faker->lastName(),
            'amat' => $this->faker->lastName(),
            'curp' => strtoupper($this->faker->bothify('????######??????##')),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->numerify('##########'),
            'sede_id' => 1,
            'organizacion_id' => null,
            'fecha_nacimiento' => $this->faker->date(),
            'fecha_inscripcion' => now()->toDateString(),
            'estado' => 'activo',
            'tags' => null,
        ];
    }
}