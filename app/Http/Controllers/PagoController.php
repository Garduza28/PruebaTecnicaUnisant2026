<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Alumno;
use App\Models\Sede;
use Illuminate\Http\Request;
use App\Http\Services\RegistrarPagoService;

class PagoController extends Controller
{

    public function create()
    {   
        // solo habian alumnos sin validaciones
        //$alumnos = Alumno::all();

       $alumnos = Alumno::validos()
    ->whereIn('id', function ($query) {
        $query->selectRaw('MIN(id)')
            ->from('alumnos')
            ->groupBy('matricula');
    })
    ->get();
    
        $sedes = Sede::validas()->orderBy('nombre')->get();

        return view('pagos.create', compact('alumnos', 'sedes'));
    }

public function buscar(Request $request)
{
    $query = $request->get('q');

    return Alumno::busqueda($query)
        ->limit(10)
        ->get();
}



public function store(Request $request)
{
    $request->validate([
        'matricula' => 'required|exists:alumnos,matricula',
        'monto' => 'required|numeric|min:1|max:99999.99',
        'fecha_pago' => 'required|date|before_or_equal:today',
        'metodo' => 'required|string',
        'sede_id' => 'required|exists:sedes,id',
    ]);

    RegistrarPagoService::execute($request->all());

    return redirect('/pagos/create')
        ->with('success', 'Pago registrado correctamente');
}


public function index(Request $request)
{
    $query = Pago::query();


    if ($request->filled('buscar')) {
        $query->where(function ($q) use ($request) {
            $q->where('matricula', 'like', '%' . $request->buscar . '%')
              ->orWhere('concepto', 'like', '%' . $request->buscar . '%');
        });
    }

    if ($request->filled('metodo')) {

        if ($request->metodo === 'desconocido') {
            $query->whereNull('metodo');
        } else {
            $query->where('metodo', $request->metodo);
        }

    }

    $pagos = $query->orderBy('id', 'desc')
                   ->paginate(3)
                   ->appends($request->all());

    return view('pagos.index', compact('pagos'));
}
}