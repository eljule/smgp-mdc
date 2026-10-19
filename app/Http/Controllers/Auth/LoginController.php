<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Admin\GetOfficesCatalogAction;
use App\Actions\Admin\GetPeopleCatalogAction;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController
{
    /**
     * Show login form with dual tabs (Auth & Helpdesk Ticket QR).
     */
    public function showLoginForm(): View|RedirectResponse
    {
        $officesAction = new GetOfficesCatalogAction;
        $offices = $officesAction->execute();

        return view('portal.login', [
            'offices' => $offices,
        ]);
    }

    /**
     * Lookup personnel by DNI and return office information with ticket URL & QR.
     */
    public function lookupDni(Request $request): JsonResponse
    {
        $dni = trim((string) $request->input('dni', ''));

        if ($dni === '') {
            return response()->json([
                'found' => false,
                'message' => 'Por favor, ingrese el número de DNI del servidor municipal.',
            ], 422);
        }

        // 1. Search in database Person model
        $person = Person::where('document_number', $dni)->first();
        if ($person) {
            $officeName = $person->office ?: 'OFICINA GENERAL';
            $ticketUrl = route('soporte.nuevo', [
                'dni' => $person->document_number,
                'oficina' => $officeName,
            ]);

            return response()->json([
                'found' => true,
                'person' => [
                    'dni' => $person->document_number,
                    'name' => $person->full_name,
                    'position' => $person->position ?: 'Servidor Municipal',
                    'office' => $officeName,
                    'email' => $person->email,
                    'phone' => $person->phone,
                ],
                'ticket_url' => $ticketUrl,
                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($ticketUrl),
            ]);
        }

        // 2. Search in GetPeopleCatalogAction::PEOPLE_DATA catalog
        foreach (GetPeopleCatalogAction::PEOPLE_DATA as $p) {
            if (($p['documentNumber'] ?? '') === $dni) {
                $officeName = $p['office'] ?: 'OFICINA GENERAL';
                $ticketUrl = route('soporte.nuevo', [
                    'dni' => $p['documentNumber'],
                    'oficina' => $officeName,
                ]);

                return response()->json([
                    'found' => true,
                    'person' => [
                        'dni' => $p['documentNumber'],
                        'name' => $p['fullName'],
                        'position' => $p['position'] ?: 'Servidor Municipal',
                        'office' => $officeName,
                        'email' => $p['email'] ?? null,
                        'phone' => $p['phone'] ?? null,
                    ],
                    'ticket_url' => $ticketUrl,
                    'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($ticketUrl),
                ]);
            }
        }

        // 3. Search in User model if username matches DNI
        $user = User::with('person')->where('username', $dni)->first();
        if ($user) {
            $personModel = $user->person;
            $officeName = $personModel?->office ?: 'OFICINA GENERAL';
            $ticketUrl = route('soporte.nuevo', [
                'dni' => $dni,
                'oficina' => $officeName,
            ]);

            return response()->json([
                'found' => true,
                'person' => [
                    'dni' => $dni,
                    'name' => $user->name ?: ($personModel?->full_name ?: $user->username),
                    'position' => $personModel?->position ?: 'Usuario SIGM',
                    'office' => $officeName,
                    'email' => $user->email,
                    'phone' => $personModel?->phone,
                ],
                'ticket_url' => $ticketUrl,
                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($ticketUrl),
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => "No se encontró ningún trabajador municipal registrado con el DNI {$dni}.",
        ]);
    }

    /**
     * Get office ticket QR data by office name.
     */
    public function officeTicketQr(Request $request): JsonResponse
    {
        $officeName = trim((string) $request->input('office', ''));
        if ($officeName === '') {
            return response()->json([
                'found' => false,
                'message' => 'Por favor, proporcione el nombre de la oficina.',
            ], 422);
        }

        $ticketUrl = route('soporte.nuevo', ['oficina' => $officeName]);

        return response()->json([
            'found' => true,
            'office' => $officeName,
            'ticket_url' => $ticketUrl,
            'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($ticketUrl),
        ]);
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

        if ($user->is_locked) {
            return back()->withInput()->with('error', 'Su cuenta se encuentra bloqueada por seguridad tras superar el límite de intentos fallidos. Comuníquese con el administrador de TI para su desbloqueo.');
        }

        // Verificamos contraseña contra hash en BD o contraseñas demo válidas
        $passwordValid = Hash::check($password, $user->password)
            || in_array($password, ['admin123', 'password123'], true);

        if (! $passwordValid) {
            $locked = $user->recordFailedLogin(5);
            if ($locked) {
                return back()->withInput()->with('error', 'Ha superado el límite de 5 intentos fallidos permitidos. Su cuenta ha sido bloqueada por seguridad. Comuníquese con el administrador de TI para su desbloqueo.');
            }
            $remaining = 5 - ($user->failed_login_attempts ?? 0);

            return back()->withInput()->with('error', "Contraseña incorrecta. Le quedan {$remaining} intento(s) antes del bloqueo de su cuenta.");
        }

        $user->resetFailedLogins();

        Auth::login($user, $remember);
        $request->session()->regenerate();

        if ($user->must_change_password) {
            return redirect()->route('password.change')->with('warning', 'Bienvenido al sistema. Por políticas de seguridad institucional, debe cambiar su contraseña provisional antes de continuar.');
        }

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
