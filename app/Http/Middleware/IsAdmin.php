<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IsAdmin
{
    /**
     * Handle an incoming request.
     * Verifica si el usuario es administrador usando el ID del rol, no el nombre.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'No tienes permisos de administrador.');
        }

        return $next($request);
    }
}

