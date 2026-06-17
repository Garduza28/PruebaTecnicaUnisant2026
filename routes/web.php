<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\Api\AlumnoApiController;

Route::get('/', function () {
    return redirect('/login');
});
    //No protegida
   //Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    //protegida
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');

    Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');

    // cambio de ruta para eliminar datos del elumno de forma correcta
    // Route::get('/alumnos/eliminar/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');


    Route::delete('/alumnos/{id}', [AlumnoController::class, 'destroy'])
    ->name('alumnos.destroy');

    Route::get('/programas', [ProgramaController::class, 'index'])->name('programas.index');
    Route::post('/programas', [ProgramaController::class, 'store'])->name('programas.store');

    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');

    Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');

    Route::get('/reportes/deudas', [ReporteController::class, 'exportarDeudas'])->name('reportes.deudas');
    Route::post('/reportes/generar', [ReporteController::class, 'generarReporteAsync'])->name('reportes.generar');

    Route::get('/api/buscar-alumnos', [ReporteController::class, 'buscarAlumnosGlobal'])->name('api.buscar');


        Route::get('/pagos/index', [PagoController::class, 'index'])->name('pagos.index');

    Route::get('/alumnos/buscar', [PagoController::class, 'buscar']);


    Route::middleware(['nivel:1,2'])->group(function () {

        Route::post('/alumnos/audit', [AlumnoController::class, 'audit'])
            ->name('alumnos.audit');

        Route::post('/alumnos/fix', [AlumnoController::class, 'fix'])
            ->name('alumnos.fix');

        Route::get('/pagos/create', [PagoController::class, 'create'])->name('pagos.create');
    Route::get('/alumnos/create', [AlumnoController::class, 'create'])->name('alumnos.create');
    });



});


Route::middleware(['auth', 'throttle:30,1'])->group(function () {

    Route::get('/api/alumnos/{matricula}', [AlumnoApiController::class, 'show'])
        ->middleware('nivel:1,2,3');

    Route::get('/api/alumnos/{matricula}/pagos', [AlumnoApiController::class, 'pagos'])
        ->middleware('nivel:1,2');
});

Route::view('/login', 'auth.login')->name('login');


Route::view('/register', 'auth.register')->name('register');

require __DIR__.'/auth.php';
