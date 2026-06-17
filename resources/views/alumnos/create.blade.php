@extends('layouts.app')

@section('title', 'Nuevo Alumno')

@section('content')

<div class="container py-3">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-0">Registrar Alumno</h3>
            <small class="text-muted">Captura los datos del estudiante</small>
        </div>

        <a href="{{ route('alumnos.index') }}" class="btn btn-light border">
            ← Regresar
        </a>

    </div>

    <form method="POST" action="/alumnos">
        @csrf

        <div class="row g-4">

        
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <h6 class="text-uppercase text-muted mb-3">Datos personales</h6>

                        <label class="form-label small fw-semibold">Nombre completo</label>
                        <input class="form-control mb-3" name="nombre_completo" required>

                        <label class="form-label small fw-semibold">Apellido paterno</label>
                        <input class="form-control mb-3" name="apat">

                        <label class="form-label small fw-semibold">Apellido materno</label>
                        <input class="form-control mb-3" name="amat">

                        <label class="form-label small fw-semibold">CURP</label>
                        <input class="form-control mb-3" name="curp" maxlength="18"
                            oninput="this.value = this.value.toUpperCase()">

                        <label class="form-label small fw-semibold">Fecha de nacimiento</label>
                        <input class="form-control mb-3" name="fecha_nacimiento" type="date" max="{{ date('Y-m-d') }}">

                    </div>
                </div>
            </div>


            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <h6 class="text-uppercase text-muted mb-3">Contacto</h6>

                        <label class="form-label small fw-semibold">Correo electrónico</label>
                        <input class="form-control mb-3" name="email">

                        <label class="form-label small fw-semibold">Teléfono</label>
                        <input class="form-control mb-3" name="telefono" maxlength="15"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                    </div>
                </div>
            </div>

      
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <h6 class="text-uppercase text-muted mb-3">Académico</h6>
                        <label class="form-label small fw-semibold">Sede</label>
                        <select name="sede_id" id="sede_id" class="form-control mb-3" required>
                            <option value=""></option>
                            @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}">
                                {{ $sede->nombre }} ({{ $sede->clave }})
                            </option>
                            @endforeach
                        </select>

                        <label class="form-label small fw-semibold">Programa</label>
                        <select name="programa_id" id="programa_id" class="form-control mb-3">
                            <option value=""></option>
                            @foreach($programas as $programa)
                            <option value="{{ $programa->id }}">
                                {{ $programa->nombre }}
                            </option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>

    
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <h6 class="text-uppercase text-muted mb-3">Finanzas</h6>

                        <label class="form-label small fw-semibold">Monto inicial</label>
                        <input class="form-control" name="monto_inicial" type="number" min="1" max="99999.99"
                            step="0.01" oninput="if(this.value.length > 7) this.value = this.value.slice(0,7)" required>

                        <small class="text-muted">Máximo: 99,999.99</small>

                    </div>
                </div>
            </div>

        </div>

  
        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary">
                Cancelar
            </a>

            <button class="btn btn-primary px-4 shadow-sm">
                Guardar alumno
            </button>

        </div>

    </form>

</div>

@endsection


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {

    $('#sede_id').select2({
        placeholder: 'Buscar sede...',
        allowClear: true,
        width: '100%'
    });

    $('#programa_id').select2({
        placeholder: 'Buscar programa...',
        allowClear: true,
        width: '100%'
    });

});
</script>

{{-- SUCCESS --}}
@if(session('success'))
<script>
    Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: "{{ session('success') }}",
    timer: 2500,
    showConfirmButton: false
});
</script>
@endif

{{-- ERROR --}}
@if(session('error'))
<script>
    Swal.fire({
    icon: 'error',
    title: 'Error',
    text: "{{ session('error') }}"
});
</script>
@endif