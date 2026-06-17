<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReporteDeuda;

class ReporteDeudaSeeder extends Seeder
{
    public function run(): void
    {
        ReporteDeuda::create([
            'nombre' => 'Reporte inicial de adeudos',
            'filtros' => null,
            'resultado' => null,
            'estado' => 'pendiente',
        ]);
    }
}