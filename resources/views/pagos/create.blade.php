@extends('layouts.app')

@section('title', 'Registrar Pago')

@section('content')

<div class="container py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="fw-semibold text-dark mb-0">Registrar Pago</h5>

        <a href="{{ route('pagos.index') }}" class="btn btn-secondary btn-sm">
            ← Regresar
        </a>

    </div>

    <div class="card p-3 mx-auto shadow-sm" style="max-width: 420px; border-radius: 10px;">

        <form method="POST" action="/pagos" id="pagoForm">
            @csrf


            <div class="mb-2">
                <label class="form-label small fw-semibold">Alumno</label>
                <select name="matricula" id="matricula" class="form-control form-control-sm" required>
                    <option value=""></option>

                    @foreach($alumnos as $alumno)
                    <option value="{{ $alumno->matricula }}">
                        {{ $alumno->matricula }} - {{ $alumno->nombre_completo }}
                    </option>
                    @endforeach
                </select>

                <small class="text-danger" id="err-matricula"></small>
            </div>


            <div class="mb-2">
                <label class="form-label small fw-semibold">Concepto</label>
                <input type="text" name="concepto" id="concepto" class="form-control form-control-sm" maxlength="40"
                    value="Colegiatura">
                <small class="text-muted">máx. 40 caracteres</small>
            </div>


            <div class="mb-2">
                <label class="form-label small fw-semibold">Monto</label>

                <input type="number" step="0.01" min="0.01" max="9999999.99" name="monto" id="monto"
                    class="form-control form-control-sm" required>

                <small class="text-muted">Debe ser mayor a 0</small>
                <small class="text-danger" id="err-monto"></small>
            </div>


            <div class="mb-2">
                <label class="form-label small fw-semibold">Fecha de pago</label>

                <input type="date" name="fecha_pago" class="form-control form-control-sm"
                    value="{{ now()->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}">
            </div>


            <div class="mb-2">
                <label class="form-label small fw-semibold">Método</label>
                <select name="metodo" class="form-control form-control-sm">
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="tarjeta">Tarjeta</option>
                </select>
            </div>


            <div class="mb-2">
                <label class="form-label small fw-semibold">Sede</label>

                <select name="sede_id" id="sede_id" class="form-control form-control-sm" required>
                    <option value=""></option>

                    @foreach($sedes as $sede)
                    <option value="{{ $sede->id }}">
                        {{ $sede->nombre }} ({{ $sede->clave }})
                    </option>
                    @endforeach
                </select>
            </div>


            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary btn-sm px-4">
                    Guardar
                </button>
            </div>

        </form>
    </div>
</div>



@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
</script>
@endif

@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {

    $('#matricula').select2({
        placeholder: 'Buscar alumno...',
        allowClear: true,
        width: '100%'
    });

    $('#sede_id').select2({
        placeholder: 'Buscar sede...',
        allowClear: true,
        width: '100%'
    });

});
</script>