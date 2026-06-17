<?php

namespace App\Helpers;

use App\Models\Alumno;

class MatriculaHelper
{
    public static function generar($prefijo = 'ALU')
    {
        $year = date('Y');

        $ultimo = Alumno::whereYear('created_at', $year)
            ->where('matricula', 'like', "{$prefijo}-{$year}-%")
            ->orderByDesc('id')
            ->first();

        $consecutivo = 1;

        if ($ultimo && preg_match('/(\d{4})$/', $ultimo->matricula, $matches)) {
            $consecutivo = (int)$matches[1] + 1;
        }

        $numero = str_pad($consecutivo, 4, '0', STR_PAD_LEFT);

        return "{$prefijo}-{$year}-{$numero}";
    }
}