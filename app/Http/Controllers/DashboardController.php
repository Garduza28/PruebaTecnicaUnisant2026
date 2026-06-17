<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Adeudo;
use App\Models\Programa;

class DashboardController extends Controller
{
    public function index()
    {
        $alumnosActivos = Alumno::where('estado', 'activo')->count();
        $totalAlumnos = Alumno::count();

        $pagosMes = Pago::where('estado', 'activo')
            ->whereBetween('fecha_pago', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])
            ->sum('monto');

        $totalInscripciones = Inscripcion::where('estado', 'activo')->count();

        $promedioPago = Pago::avg('monto') ?? 0;

        $programasTop = Programa::withCount('inscripciones')
            ->orderByDesc('inscripciones_count')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'alumnosActivos',
            'totalAlumnos',
            'pagosMes',
            'totalInscripciones',
            'promedioPago',
            'programasTop'
        ));
    }
}