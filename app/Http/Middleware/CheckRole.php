<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        $user = $request->user();

        // Verificar si el usuario tiene al menos uno de los roles
        if (!$user || !in_array($user->role, $roles)) {
            // Redirigir a la página anterior con un mensaje de error
            return redirect()->back();
        }

        return $next($request);
    }
}
