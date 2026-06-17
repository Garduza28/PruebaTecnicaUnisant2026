<?php

namespace Database\Factories;

use App\Models\Adeudo;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdeudoFactory extends Factory
{
    protected $model = Adeudo::class;

public function definition(): array
{
    return [
        'alumno_id' => \App\Models\Alumno::factory(),
        'nombre' => 'Adeudo de prueba',
        'monto' => 1000.50,
        'plazos' => 3,
        'desde' => now(),
        'dias' => 31,
        'divisa_id' => 1,
    ];
}
}