<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Handle an incoming request.
     * Enforce password change if must_change_password flag is active.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && (bool) $user->must_change_password) {
            $allowedRoutes = [
                'password.change',
                'password.update',
                'logout',
                'logout.post',
            ];

            $currentRouteName = (string) $request->route()?->getName();
            if (! in_array($currentRouteName, $allowedRoutes, true)) {
                return redirect()->route('password.change')->with('warning', 'Por motivos de seguridad institucional, debe cambiar su contraseña provisional antes de continuar.');
            }
        }

        return $next($request);
    }
}
