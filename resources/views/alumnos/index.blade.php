@extends('layouts.app')

@section('title', 'Alumnos')

@section('content')




<div class="d-flex justify-content-between align-items-center mb-3">

    <h2 class="mb-0">Lista de Alumnos</h2>

    @if(auth()->user()?->nivel_id <= 2) <a href="{{ route('alumnos.create') }}" class="btn btn-success">
        + Crear registro
        </a>
        @endif

</div>


<form method="GET" action="{{ route('alumnos.index') }}" class="mb-3">
    <div class="row">

        <div class="col-md-4">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar..."
                value="{{ request('buscar') }}">
        </div>

        <div class="col-md-3">
            <select name="sede_id" class="form-control">
                <option value="">Todas las sedes</option>
                @foreach($sedes as $sede)
                <option value="{{ $sede->id }}" @selected(request('sede_id')==$sede->id)>
                    {{ $sede->nombre }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>

    </div>
</form>

<table class="table table-bordered">

    <thead>
        <tr>
            <th>Matrícula</th>
            <th>Nombre</th>
            <th>Sede</th>
            <th>Estado</th>

            @if(auth()->user()?->nivel_id <= 3) <th>Acciones</th>
                @endif

        </tr>
    </thead>

    <tbody>

        @forelse($alumnos as $alumno)
        <tr>
            <td>{{ $alumno->matricula }}</td>
            <td>{{ $alumno->nombre_completo }}</td>
            <td>{{ optional($alumno->sede)->nombre ?? 'N/A' }}</td>

            <td>
                <span class="badge bg-{{ $alumno->estado === 'activo' ? 'success' : 'secondary' }}">
                    {{ $alumno->estado }}
                </span>
            </td>


            @if(auth()->user()?->nivel_id <=[1,2,3]) <td class="d-flex gap-1">


                <a href="#" class="btn btn-info btn-sm">
                    Ver
                </a>


                @if(auth()->user()?->nivel_id <= 2) <a href="#" class="btn btn-primary btn-sm">
                    Editar
                    </a>
                    @endif

                    @if(auth()->user()?->nivel_id == 1)
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                        data-id="{{ $alumno->id }}" data-name="{{ $alumno->nombre_completo }}">
                        Eliminar
                    </button>
                    @endif

                    </td>
                    @endif

        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No hay alumnos registrados</td>
        </tr>
        @endforelse

    </tbody>

</table>
@if ($alumnos->hasPages())
<div class="d-flex justify-content-center mt-4">

    <nav>
        <ul class="pagination pagination-sm align-items-center gap-1 mb-0">


            <li class="page-item {{ $alumnos->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $alumnos->previousPageUrl() }}">
                    ‹
                </a>
            </li>


            @foreach ($alumnos->getUrlRange(1, $alumnos->lastPage()) as $page => $url)
            <li class="page-item {{ $page == $alumnos->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">
                    {{ $page }}
                </a>
            </li>
            @endforeach


            <li class="page-item {{ $alumnos->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $alumnos->nextPageUrl() }}">
                    ›
                </a>
            </li>

        </ul>
    </nav>

</div>
@endif


<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p>¿Estás seguro de eliminar al alumno?</p>

                <strong id="alumnoName"></strong>

                <div id="warningBox" class="alert alert-danger mt-3 d-none">
                    Este alumno tiene restricciones y no puede eliminarse.
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        Sí, eliminar
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="fixModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Confirmar corrección
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <p>
                    Esta acción ejecutará la corrección automática de inconsistencias detectadas en el sistema.
                </p>

                <div class="alert alert-warning mb-0">
                    Asegúrate de haber revisado los datos antes de continuar.
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <form method="POST" action="{{ route('alumnos.fix') }}">
                    @csrf

                    <button type="submit" class="btn btn-danger">
                        Sí, corregir
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>
@if(in_array(auth()->user()?->nivel_id, [1]))

<div class="mb-3">

    <button
        class="btn btn-danger btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#fixModal">
        Corregir inconsistencias
    </button>

    <br> <br> 

      <small class="text-muted d-block mb-1">
        Visible únicamente para administradores.
    </small>

</div>

@endif
@endsection

@push('scripts')


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

  
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            timer: 3000,
            showConfirmButton: false,
            position: 'center'
        });
    @endif

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: @json(session('success')),
            timer: 2500,
            showConfirmButton: false,
            position: 'center'
        });
    @endif



    const modal = document.getElementById('deleteModal');

    modal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

document.getElementById('deleteForm').action = `/alumnos/${id}`;        
        document.getElementById('alumnoName').textContent = name;

    });

});
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endpush