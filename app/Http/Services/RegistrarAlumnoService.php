<?php

namespace App\Http\Services;

use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use App\Helpers\MatriculaHelper;

class RegistrarAlumnoService
{
    public function ejecutar(array $data)
    {
        return DB::transaction(function () use ($data) {

            $this->validarDatos($data);
            

            $alumno = Alumno::create([
                'matricula' => MatriculaHelper::generar('ALU'),
                'nombre_completo' => $data['nombre_completo'],
                'apat' => $data['apat'] ?? null,
                'amat' => $data['amat'] ?? null,
                'curp' => $data['curp'] ?? null,
                'email' => $data['email'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'sede_id' => $data['sede_id'],
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
            ]);

            if (!empty($data['programa_id'])) {
                Inscripcion::create([
                    'alumno_id' => $alumno->id,
                    'programa_id' => $data['programa_id'],
                    'sede_id' => $data['sede_id'],
                    'estado' => 'activo',
                    'fecha_inscripcion' => now(),
                ]);
            }

            if (!empty($data['monto_inicial'])) {
                Pago::create([
                    'matricula' => $alumno->matricula,
                    'concepto' => 'Inscripción',
                    'monto' => $data['monto_inicial'],
                    'fecha_pago' => now(),
                    'sede_id' => $data['sede_id'],
                    'estado' => 'activo',
                ]);
            }

            return $alumno;
        });
    }

    private function validarDatos(array $data)
    {


        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email inválido');
        }

        if (!\App\Models\Sede::find($data['sede_id'] ?? null)) {
            throw new \Exception('La sede no existe');
        }

        if (!empty($data['programa_id']) && !\App\Models\Programa::find($data['programa_id'])) {
            throw new \Exception('El programa no existe');
        }
    }
}