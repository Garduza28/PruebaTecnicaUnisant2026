@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-4">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white text-center fw-bold">
                Iniciar Sesión
            </div>

            <div class="card-body p-4">

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Correo electrónico</label>
                        <input 
                            type="email" 
                            name="email" 
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="ejemplo@correo.com"
                            required
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contraseña</label>

                        <div class="position-relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="form-control pe-5 @error('password') is-invalid @enderror"
                                placeholder="••••••••"
                                required
                            >

                            <i id="togglePassword"
                               class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3"
                               style="cursor: pointer; color: #6c757d;">
                            </i>
                        </div>

                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    @if ($errors->has('login'))
                        <div class="alert alert-danger py-2">
                            {{ $errors->first('login') }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        Entrar
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('password');
    const icon = document.getElementById('togglePassword');

    icon.addEventListener('click', function () {

        const isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';

        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });

});
</script>
@endsection