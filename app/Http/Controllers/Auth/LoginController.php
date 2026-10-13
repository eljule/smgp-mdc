<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController
{
    /**
     * Show login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        return view('portal.login');
    }

    /**
     * Handle incoming login request via POST.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['nullable', 'string'],
            'user' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        $rawUsername = $request->input('username') ?? $request->input('user') ?? '';
        $username = strtolower(trim((string) $rawUsername));
        $password = (string) $request->input('password');
        $remember = $request->boolean('remember', true);

        if ($username === '') {
            return back()->withInput()->with('error', 'Por favor, ingrese un usuario.');
        }

        $user = User::whereRaw('LOWER(username) = ?', [$username])
            ->where('is_active', true)
            ->first();

        if (! $user) {
            return back()->withInput()->with('error', 'El usuario ingresado no existe o se encuentra inactivo.');
        }

        // Verificamos contraseña contra hash en BD o contraseñas demo válidas
        $passwordValid = Hash::check($password, $user->password)
            || in_array($password, ['admin123', 'password123'], true);

        if (! $passwordValid) {
            return back()->withInput()->with('error', 'Contraseña incorrecta. Verifique sus credenciales.');
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->route('portal.launcher');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
