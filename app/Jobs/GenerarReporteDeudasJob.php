<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Adeudo;
use App\Models\ReporteDeuda;
use Illuminate\Foundation\Bus\Dispatchable;



class GenerarReporteDeudasJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $reporteId
    ) {}


    public function handle(): void
    {
        try {

            $reporte = ReporteDeuda::find($this->reporteId);

            // ANTES: sin validación de existencia
            // $reporte = ReporteDeuda::find(...);

            // AHORA: validación segura
            if (!$reporte) {
                return;
            }

            $reporte->update(['estado' => 'procesando']);

            $resultado = [];

            // ❌ ANTES: get() cargaba todo en memoria
            // $adeudos = Adeudo::with('alumno')->get();

            // ✅ AHORA: chunk para escalabilidad
            Adeudo::with('alumno')
                ->chunk(100, function ($adeudos) use (&$resultado) {

                    foreach ($adeudos as $adeudo) {

                        if (!$adeudo->alumno) {
                            continue;
                        }

                        $resultado[] = [
                            'alumno' => $adeudo->alumno->nombre_completo,
                            'monto' => $adeudo->monto,
                        ];
                    }
                });

            $reporte->update([
                'resultado' => json_encode($resultado),
                'estado' => 'completado'
            ]);

        } catch (\Throwable $e) {

            // ❌ ANTES: sin trazabilidad
            // ahora agregamos logging real

            \Log::error('Fallo en GenerarReporteDeudasJob', [
                'reporte_id' => $this->reporteId,
                'error' => $e->getMessage()
            ]);

            if (isset($reporte)) {
                $reporte->update([
                    'estado' => 'fallido'
                ]);
            }

            throw $e;
        }
    }
}
