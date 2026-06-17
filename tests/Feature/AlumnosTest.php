<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Alumno;
use App\Models\Adeudo;
use App\Models\Inscripcion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlumnosTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_se_puede_eliminar_alumno_con_adeudos()
    {
        $this->actingAs(User::factory()->create());

        $alumno = Alumno::create([
            'matricula' => 'A001',
            'nombre_completo' => 'Test User',
            'apat' => 'Test',
            'amat' => 'User',
            'sede_id' => 1,
            'estado' => 'activo',
        ]);

        Adeudo::create([
            'alumno_id' => $alumno->id,
            'nombre' => 'Adeudo prueba',
            'monto' => 100,
            'plazos' => 1,
            'desde' => now(),
            'dias' => 31,
            'divisa_id' => 1,
        ]);

        $response = $this->delete("/alumnos/{$alumno->id}");

        $response->assertRedirect(route('alumnos.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('alumnos', [
            'id' => $alumno->id
        ]);
    }


    public function test_no_se_puede_eliminar_con_inscripciones_activas()
    {
        $this->actingAs(User::factory()->create());

        $alumno = Alumno::create([
            'matricula' => 'A002',
            'nombre_completo' => 'Test User 2',
            'apat' => 'Test',
            'amat' => 'User',
            'sede_id' => 1,
            'estado' => 'activo',
        ]);

        Inscripcion::create([
            'alumno_id' => $alumno->id,
            'programa_id' => 1,
            'sede_id' => 1,
            'estado' => 'activo',
        ]);

       $response = $this->delete("/alumnos/{$alumno->id}");

        $response->assertRedirect(route('alumnos.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('alumnos', [
            'id' => $alumno->id
        ]);
    }

    public function test_si_no_tiene_restricciones_se_puede_eliminar()
    {
        $this->actingAs(User::factory()->create());

        $alumno = Alumno::create([
            'matricula' => 'A003',
            'nombre_completo' => 'Test User 3',
            'apat' => 'Test',
            'amat' => 'User',
            'sede_id' => 1,
            'estado' => 'activo',
        ]);

        $response = $this->delete("/alumnos/{$alumno->id}");

        $response->assertRedirect(route('alumnos.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('alumnos', [
            'id' => $alumno->id
        ]);
    }
}