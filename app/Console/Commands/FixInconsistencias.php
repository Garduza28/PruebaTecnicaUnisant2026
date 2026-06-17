<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumno;
use App\Models\Pago;
use App\Models\Adeudo;
use App\Models\Inscripcion;

class FixInconsistencias extends Command
{
    protected $signature = 'fix:inconsistencias';
    protected $description = 'Corrige automáticamente inconsistencias H1-H37 del sistema';

    public function handle()
    {
        $this->info(" Iniciando limpieza automática...\n");

    
        $h1 = Alumno::whereNotNull('curp')->get();
        foreach ($h1 as $a) {
            $a->curp = trim($a->curp);
            $a->save();
        }

    
        $h2 = Alumno::whereNotNull('email')->get();
        foreach ($h2 as $a) {
            $a->email = strtolower(trim($a->email));
            $a->save();
        }

 
        $h3 = Alumno::whereNotNull('telefono')->get();
        foreach ($h3 as $a) {
            $a->telefono = preg_replace('/[^0-9]/', '', $a->telefono);
            $a->save();
        }

        $h4 = Alumno::whereNotNull('nombre_completo')->get();

         foreach ($h4 as $a) {

         $nombre = trim($a->nombre_completo);
  
         if ($nombre === '') {
          $a->nombre_completo = 'SIN NOMBRE';
         } else {
           $a->nombre_completo = ucwords(strtolower($nombre));
       }

    $a->save();
}


        $h5 = Alumno::all();
        foreach ($h5 as $a) {
            $a->matricula = strtoupper(trim($a->matricula));
            $a->save();
        }

        $duplicados = Alumno::select('matricula')
            ->groupBy('matricula')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('matricula');

        foreach ($duplicados as $matricula) {
            $alumnos = Alumno::where('matricula', $matricula)->get();

            $alumnos->shift(); // conserva el primero

            foreach ($alumnos as $dup) {
                $dup->delete();
            }
        }

        Alumno::whereNotIn('estado', ['activo', 'inactivo'])
            ->update(['estado' => 'activo']);


        Pago::where('monto', '<', 0)->get()->each(function ($p) {
            $p->monto = abs($p->monto);
            $p->save();
        });

        // H9 - Pagos sin matrícula → eliminar
        Pago::whereNull('matricula')->delete();


        // H10 - Adeudos negativos → abs()
        Adeudo::where('monto', '<', 0)->get()->each(function ($a) {
            $a->monto = abs($a->monto);
            $a->save();
        });

        // H11 - Adeudos sin alumno → eliminar
        Adeudo::whereNull('alumno_id')->delete();


        // H12 - estados inválidos
        Inscripcion::whereNotIn('estado', ['activo', 'baja', 'inactivo'])
            ->update(['estado' => 'activo']);

        // H13 - fechas corruptas
        Inscripcion::whereColumn('fecha_termino', '<', 'fecha_inscripcion')
            ->update([
                'fecha_termino' => now()->addMonths(6)
            ]);

        $this->info("Limpieza automática completada correctamente");

        return 0;
    }
}