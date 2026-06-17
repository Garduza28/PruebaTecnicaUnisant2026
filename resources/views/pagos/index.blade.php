@extends('layouts.app')

@section('title', 'Pagos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">

    <h2 class="mb-0">Historial de Pagos</h2>

    {{-- 🔥 CREAR PAGO (solo nivel 1 y 2) --}}
    @if(auth()->user()?->nivel_id <= 2)
        <a href="{{ route('pagos.create') }}" class="btn btn-success">
            + Crear pago
        </a>
    @endif

</div>

<!-- FILTROS -->
<form method="GET" action="{{ route('pagos.index') }}" class="mb-3">
    <div class="row">

        <div class="col-md-4">
            <input type="text"
                   name="buscar"
                   class="form-control"
                   placeholder="Buscar por matrícula o concepto..."
                   value="{{ request('buscar') }}">
        </div>

        <div class="col-md-3">
            <select name="metodo" class="form-control">
                <option value="">Todos los métodos</option>

                <option value="efectivo" @selected(request('metodo') == 'efectivo')>
                    Efectivo
                </option>

                <option value="tarjeta" @selected(request('metodo') == 'tarjeta')>
                    Tarjeta
                </option>

                <option value="transferencia" @selected(request('metodo') == 'transferencia')>
                    Transferencia
                </option>

                <option value="desconocido" @selected(request('metodo') == 'desconocido')>
                    Desconocido
                </option>

            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>

    </div>
</form>

<table class="table table-bordered">

    <thead>
        <tr>
            <th>Matrícula</th>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Método</th>
            <th>Fecha</th>
            <th>Estado</th>

            {{-- ACCIONES SOLO NIVEL 1-3 --}}
            @if(auth()->user()?->nivel_id <= 3)
                <th>Acciones</th>
            @endif
        </tr>
    </thead>

    <tbody>

        @forelse($pagos as $pago)
        <tr>

            <td>{{ $pago->matricula }}</td>

            <td>
                {{ filled(trim($pago->concepto)) ? $pago->concepto : 'Sin concepto' }}
            </td>

            <td>
                ${{ number_format($pago->monto, 2) }}
            </td>

            {{-- MÉTODO --}}
            <td>
                @php
                    $metodo = $pago->metodo ?? 'desconocido';

                    $colorMetodo = match($metodo) {
                        'efectivo' => 'success',
                        'tarjeta' => 'primary',
                        'transferencia' => 'warning',
                        default => 'secondary'
                    };
                @endphp

                <span class="badge bg-{{ $colorMetodo }}">
                    {{ $metodo }}
                </span>
            </td>

            <td>
                {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
            </td>

            <td>
                @php
                    $colorEstado = $pago->estado === 'pagado' ? 'success' : 'warning';
                @endphp

                <span class="badge bg-{{ $colorEstado }}">
                    {{ $pago->estado }}
                </span>
            </td>

           
            @if(auth()->user()?->nivel_id <= 3)
            <td class="d-flex gap-1">

                <button class="btn btn-info btn-sm">
                    Ver
                </button>

               
                @if(auth()->user()?->nivel_id <= 2)
                    <button class="btn btn-primary btn-sm">
                        Editar
                    </button>
                @endif

            
                @if(auth()->user()?->nivel_id == 1)
                    <button class="btn btn-danger btn-sm">
                        Eliminar
                    </button>
                @endif

            </td>
            @endif

        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No hay pagos registrados</td>
        </tr>
        @endforelse

    </tbody>

</table>


@if ($pagos->hasPages())
<div class="d-flex justify-content-center mt-4">

    <nav>
        <ul class="pagination pagination-sm align-items-center gap-1 mb-0">

            <li class="page-item {{ $pagos->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $pagos->previousPageUrl() }}">‹</a>
            </li>

            @foreach ($pagos->getUrlRange(1, $pagos->lastPage()) as $page => $url)
                <li class="page-item {{ $page == $pagos->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <li class="page-item {{ $pagos->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $pagos->nextPageUrl() }}">›</a>
            </li>

        </ul>
    </nav>

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

});
</script>

@endpush