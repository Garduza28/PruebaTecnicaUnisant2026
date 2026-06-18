@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid py-3">


    <div class="row g-3">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body">
                    <small class="text-muted">Alumnos activos</small>
                    <h3 class="mb-0 fw-bold text-dark">{{ $alumnosActivos }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body">
                    <small class="text-muted">Total alumnos</small>
                    <h3 class="mb-0 fw-bold text-dark">{{ $totalAlumnos }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body">
                    <small class="text-muted">Pagos del mes</small>
                    <h3 class="mb-0 fw-bold text-success">
                        ${{ number_format($pagosMes, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body">
                    <small class="text-muted">Inscripciones</small>
                    <h3 class="mb-0 fw-bold text-dark">{{ $totalInscripciones }}</h3>
                </div>
            </div>
        </div>

    </div>


    <div class="row mt-4 g-3">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 bg-light h-100">
                <div class="card-body">
                    <small class="text-muted">Promedio de pago</small>

                    <h2 class="fw-bold text-primary mt-2">
                        ${{ number_format($promedioPago, 2) }}
                    </h2>

                    <small class="text-muted">
                        Promedio general de pagos registrados
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 bg-light h-100">
                <div class="card-body">
                    <small class="text-muted">Top programas</small>

                    <ul class="list-group list-group-flush mt-2">
                        @foreach($programasTop as $programa)
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <span class="text-dark">
                                {{ filled(trim($programa->nombre ?? '')) ? trim($programa->nombre) : 'Programa sin
                                nombre' }}
                            </span>
                            <span class="badge bg-dark rounded-pill">
                                {{ $programa->inscripciones->count() }}
                            </span>
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>

    </div>

</div>

@endsection