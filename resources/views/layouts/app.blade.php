<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SII Prueba Técnica')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="/dashboard">
            SII Prueba
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarPrincipal"
                aria-controls="navbarPrincipal"
                aria-expanded="false"
                aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarPrincipal">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/alumnos">
                        Alumnos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/programas">
                        Programas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/pagos/index">
                        Pagos
                    </a>
                </li>

            </ul>

            <form method="POST" action="/logout" class="d-flex">
                @csrf

                <button class="btn btn-outline-light btn-sm" type="submit">
                    Cerrar sesión
                </button>

            </form>

        </div>

    </div>
</nav>

<div class="container mt-4">


    @yield('content')

</div>

@stack('scripts')

</body>
</html>