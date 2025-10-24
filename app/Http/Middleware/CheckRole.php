<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        // Obtener el usuario autenticado usando el modelo Usuario
        $usuario = Usuario::find(Auth::id());
        
        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Usuario no encontrado');
        }

        // Verificar si el rol del usuario está en los roles permitidos
        if (!in_array($usuario->id_rol, array_map('intval', $roles))) {
            abort(403, 'No tienes permisos para acceder a esta página');
        }

        return $next($request);
    }
}
