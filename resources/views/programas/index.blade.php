@extends('layouts.app')

@section('title', 'Programas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">

    <h2 class="mb-0">Programas Académicos</h2>

</div>


<form method="GET" action="{{ route('programas.index') }}" class="mb-3">
    <div class="row">

        <div class="col-md-5">
            <input type="text"
                   name="buscar"
                   class="form-control"
                   placeholder="Buscar programa..."
                   value="{{ request('buscar') }}">
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('programas.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>

    </div>
</form>


<table class="table table-bordered">

    <thead>
        <tr>
            <th>Programa</th>
            <th>Inscripción</th>
            <th>Costo Materia</th>
            <th>Total Materias</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>

        @forelse($programas as $programa)
        <tr>

<td>
    {{ !empty($programa->nombre) ? $programa->nombre : 'No definido' }}
</td>
            <td>
                ${{ number_format($programa->inscripcion, 2) }}
            </td>

            <td>
                ${{ number_format($programa->precio_materia, 2) }}
            </td>

            <td>
                <span class="badge bg-info">
                    {{ $programa->materias_count }}
                </span>
            </td>

            <td class="d-flex gap-1">

                <button class="btn btn-sm btn-info">
                    Ver
                </button>

                @if(auth()->user()?->nivel_id <= 2)
                <button class="btn btn-sm btn-primary">
                    Editar
                </button>
                @endif

            </td>

        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No hay programas registrados</td>
        </tr>
        @endforelse

    </tbody>

</table>


@if ($programas->hasPages())
<div class="d-flex justify-content-center mt-4">

    <nav>
        <ul class="pagination pagination-sm align-items-center gap-1 mb-0">

          
            <li class="page-item {{ $programas->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $programas->previousPageUrl() }}">‹</a>
            </li>

       
            @foreach ($programas->getUrlRange(1, $programas->lastPage()) as $page => $url)
                <li class="page-item {{ $page == $programas->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            
            <li class="page-item {{ $programas->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $programas->nextPageUrl() }}">›</a>
            </li>

        </ul>
    </nav>

</div>
@endif

@endsection