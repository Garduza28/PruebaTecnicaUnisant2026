<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Adeudo;
use App\Models\Pago;
use Carbon\Carbon;

class AuditInconsistencias extends Command
{
    protected $signature = 'audit:inconsistencias';
    protected $description = 'Audita inconsistencias H1-H37 del sistema';

    public function handle()
    {
        $this->info("Iniciando auditoría de inconsistencias...\n");

        // H1: CURP vacía o inválida
        $h1 = Alumno::whereNull('curp')
            ->orWhere('curp', '')
            ->count();

        // H2: Email inválido
        $h2 = Alumno::whereNull('email')
            ->orWhere('email', '')
            ->orWhere('email', 'NOT LIKE', '%@%')
            ->count();

        // H3: Teléfono con letras
        $h3 = Alumno::whereRaw("telefono REGEXP '[A-Za-z]'")->count();

        // H4: Nombres vacíos
        $h4 = Alumno::whereNull('nombre_completo')
           ->orWhere('nombre_completo', '')
           ->orWhereRaw("TRIM(nombre_completo) = ''")
           ->count();

        // H5: Matrícula vacía
        $h5 = Alumno::whereNull('matricula')
            ->orWhere('matricula', '')
            ->count();

        // H6: Matrículas duplicadas
        $h6 = Alumno::select('matricula')
            ->groupBy('matricula')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        // H7: Estados inválidos
        $h7 = Alumno::whereNotIn('estado', ['activo', 'inactivo'])->count();

        // H8: Fechas de nacimiento futuras
        $h8 = Alumno::where('fecha_nacimiento', '>', now())->count();

   
        // H9: Inscripción sin alumno
        $h9 = Inscripcion::whereNull('alumno_id')->count();

        // H10: Inscripción activa sin programa
        $h10 = Inscripcion::whereNull('programa_id')->count();

        // H11: Estado inválido
        $h11 = Inscripcion::whereNotIn('estado', ['activo', 'inactivo', 'baja'])->count();


        // H12: Adeudos con monto negativo
        $h12 = Adeudo::where('monto', '<', 0)->count();

        // H13: Adeudos sin alumno
        $h13 = Adeudo::whereNull('alumno_id')->count();



        // H14: Pagos con monto negativo
        $h14 = Pago::where('monto', '<', 0)->count();

        // H15: Pagos sin matrícula
        $h15 = Pago::whereNull('matricula')->count();


        $h16 = Alumno::select('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->count();

    
        $h17 = Alumno::whereNull('sede_id')->count();

        // H18: inscripciones duplicadas alumno+programa
        $h18 = Inscripcion::select('alumno_id', 'programa_id')
            ->groupBy('alumno_id', 'programa_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();

  
        // H19: fecha inscripción futura
        $h19 = Inscripcion::where('fecha_inscripcion', '>', now())->count();

        // H20: fecha término menor a inscripción
        $h20 = Inscripcion::whereColumn('fecha_termino', '<', 'fecha_inscripcion')->count();

      

        $report = [
            'H1 CURP vacía' => $h1,
            'H2 Email inválido' => $h2,
            'H3 Teléfono inválido' => $h3,
            'H4 Nombre vacío' => $h4,
            'H5 Matrícula vacía' => $h5,
            'H6 Matrículas duplicadas' => $h6,
            'H7 Estados inválidos' => $h7,
            'H8 Fecha nacimiento futura' => $h8,
            'H9 Inscripción sin alumno' => $h9,
            'H10 Inscripción sin programa' => $h10,
            'H11 Estado inscripción inválido' => $h11,
            'H12 Adeudos negativos' => $h12,
            'H13 Adeudos sin alumno' => $h13,
            'H14 Pagos negativos' => $h14,
            'H15 Pagos sin matrícula' => $h15,
            'H16 Emails duplicados' => $h16,
            'H17 Alumnos sin sede' => $h17,
            'H18 Inscripciones duplicadas' => $h18,
            'H19 Fecha inscripción futura' => $h19,
            'H20 Fechas inconsistentes' => $h20,
        ];

        $this->info("\n REPORTE DE INCONSISTENCIAS:\n");

        foreach ($report as $key => $value) {
            $this->line("$key: $value");
        }

        $this->info("\nAuditoría finalizada");

        return 0;
    }
}