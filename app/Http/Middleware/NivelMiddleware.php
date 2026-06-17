<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NivelMiddleware
{
public function handle(Request $request, Closure $next, ...$niveles): Response
{
    $user = $request->user();

    if (!$user) {
        return redirect('/login');
    }

    if (in_array($user->nivel_id, $niveles)) {
        return $next($request);
    }

    //return redirect('/dashboard');
    abort(403, 'No tienes permisos para acceder a esta sección');
}

}
