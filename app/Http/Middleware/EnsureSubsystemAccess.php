<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubsystemAccess
{
    /**
     * Handle an incoming request and ensure user is authorized for the given subsystem.
     */
    public function handle(Request $request, Closure $next, string $subsystem): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder al sistema.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->canAccessSubsystem($subsystem)) {
            return $next($request);
        }

        // Si es usuario con rol Personal General / Reportante
        if ($user->isReportante()) {
            $dni = $user->person?->document_number ?: (ctype_digit($user->username) ? $user->username : '');

            return redirect()->route('login', [
                'tab' => 'helpdesk',
                'dni' => $dni,
            ])->with('info', 'Acceso denegado: Su cuenta tiene el rol de Personal General / Reportante y únicamente tiene acceso al módulo de Mesa de Ayuda para el registro de tickets.');
        }

        return redirect()->route('portal.launcher')->with('error', "No tiene permisos para acceder al módulo de {$subsystem}.");
    }
}