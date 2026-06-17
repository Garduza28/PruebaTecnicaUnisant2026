<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Sede;
use App\Models\Programa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Http\Services\RegistrarAlumnoService;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $alumnos = Alumno::validos()
            ->whereIn('id', function ($sub) {
                $sub->selectRaw('MIN(id)')
                    ->from('alumnos')
                    ->groupBy('matricula');
            })
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $query->where('nombre_completo', 'like', "%{$request->buscar}%");
            })
            ->when($request->filled('sede_id'), function ($query) use ($request) {
                $query->where('sede_id', $request->sede_id);
            })
            ->paginate(10)
            ->withQueryString();

        $sedes = Sede::validas()->get();

        return view('alumnos.index', compact('alumnos', 'sedes'));
    }

    public function create()
    {
        $sedes = Sede::validas()->get();
        $programas = Programa::validos()->get();

        return view('alumnos.create', compact('sedes', 'programas'));
    }

    public function store(Request $request, RegistrarAlumnoService $service)
    {
        $request->validate([
            'nombre_completo' => 'required',
            'sede_id' => 'required|exists:sedes,id',
        ]);

    
        try {
            $service->ejecutar($request->all());

            return redirect()
                ->route('alumnos.index')
                ->with('success', 'Alumno creado correctamente');

        } catch (\Exception $e) {

            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

public function destroy($id)
{
    $alumno = Alumno::find($id);

    if (!$alumno) {
        return redirect()
            ->route('alumnos.index')
            ->with('error', 'Alumno no encontrado');
    }

    if ($alumno->adeudos()->exists()) {
        return redirect()
            ->route('alumnos.index')
            ->with('error', 'No se puede eliminar: tiene adeudos pendientes');
    }

    if ($alumno->inscripciones()->where('estado', 'activo')->exists()) {
        return redirect()
            ->route('alumnos.index')
            ->with('error', 'No se puede eliminar: tiene inscripciones activas');
    }

    $alumno->delete();

    return redirect()
        ->route('alumnos.index')
        ->with('success', 'Alumno eliminado correctamente');
}
    public function audit()
    {
        abort_unless(auth()->user()->nivel_id === 1, 403);

        Artisan::call('audit:inconsistencias');

        return back()->with('success', 'Auditoría ejecutada correctamente');
    }

    public function fix()
    {
        abort_unless(auth()->user()->nivel_id === 1, 403);

        Artisan::call('fix:inconsistencias');

        return back()->with('success', 'Limpieza ejecutada correctamente');
    }
}