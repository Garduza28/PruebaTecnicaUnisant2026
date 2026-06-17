<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Models\Materia;
use Illuminate\Http\Request;

class ProgramaController extends Controller
{
public function index(Request $request)
{
    $query = Programa::query();


    if ($request->filled('buscar')) {
        $query->where('nombre', 'like', '%' . $request->buscar . '%');
    }

    $programas = $query->withCount('materias')
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->appends($request->all());

    return view('programas.index', compact('programas'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'inscripcion' => 'required|numeric|min:0',
            'precio_materia' => 'required|numeric|min:0',
            'materias' => 'array'
        ]);

        $programa = Programa::create([
            'nombre' => $data['nombre'],
            'inscripcion' => $data['inscripcion'],
            'precio_materia' => $data['precio_materia'],
        ]);

        if (!empty($data['materias'])) {
            $programa->materias()->sync($data['materias']);
        }

        return redirect('/programas')
            ->with('success', 'Programa creado correctamente');
    }

    
}