<?php

namespace App\Http\Services;

use App\Models\Pago;
use App\Models\Alumno;

class RegistrarPagoService
{
 
    public static function execute(array $data): Pago
    {
    
        $alumno = Alumno::where('matricula', $data['matricula'])->first();

        $sedeId = $data['sede_id'] ?? $alumno?->sede_id;

        $pago = Pago::create([
            'matricula'   => $data['matricula'],
            'concepto'    => $data['concepto'] ?? 'Pago general',
            'monto'       => $data['monto'],
            'fecha_pago'  => $data['fecha_pago'] ?? now(),
            'metodo'      => $data['metodo'] ?? 'efectivo',
            'sede_id'     => $sedeId,
            'estado'      => 'activo',
        ]);

        $comision = $data['monto'] * 0.05;

        $pago->update([
            'nota' => 'Comisión: ' . $comision
        ]);

        return $pago;
    }
}