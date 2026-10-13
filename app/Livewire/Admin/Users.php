<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\Admin\GetUsersCatalogAction;
use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.subsystem', [
    'title' => 'Gestión de Usuarios y Permisos - SIGM Castilla',
    'moduleName' => 'Panel de Administración',
    'subsystem' => 'admin',
    'activeTab' => 'users',
    'counts' => [
        'people' => 46,
        'users' => 49,
        'roles' => 4,
        'permissions' => 19,
    ],
])]
#[Title('Gestión de Usuarios y Permisos - SIGM Castilla')]
class Users extends Component
{
    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'rol')]
    public string $roleFilter = 'TODOS';

    #[Url(as: 'pagina')]
    public int $page = 1;

    public int $perPage = 10;

    // Modal de edición de permisos
    public bool $showEditModal = false;

    public ?int $selectedUserId = null;

    public ?array $selectedUser = null;

    public string $selectedUserRole = '';

    public array $userSubsystems = [];

    // Modal interactivo: Crear Usuario y Otorgar Permisos
    public bool $showUserModal = false;

    public bool $showCreateModal = false; // Compatibilidad

    public ?string $userModalError = null;

    public ?int $userFormPersonId = null;

    public string $userFormFullName = '';

    public string $userFormUsername = '';

    public string $userFormPassword = '';

    public string $userFormRole = 'Técnico de Soporte e ITAM';

    public array $userFormModules = ['admin', 'itam', 'helpdesk'];

    public string $flashMessage = '';

    public function setRoleFilter(string $role): void
    {
        $this->roleFilter = $role;
        $this->page = 1;
    }

    public function updatingSearch(): void
    {
        $this->page = 1;
    }

    public function gotoPage(int $targetPage): void
    {
        $this->page = max(1, $targetPage);
    }

    public function updatedPerPage(): void
    {
        $this->page = 1;
    }

    public function openEditPermissions(int $userId, ?GetUsersCatalogAction $action = null): void
    {
        $action ??= app(GetUsersCatalogAction::class);
        $user = $action->find($userId);

        if ($user) {
            $this->selectedUserId = $userId;
            $this->selectedUser = $user;

            $role = $user['role'] ?? $user['primaryRole'] ?? 'Administrador Central';
            $this->selectedUserRole = match (mb_strtoupper($role)) {
                'ADMINISTRADOR CENTRAL' => 'Administrador Central',
                'TÉCNICO DE SOPORTE E ITAM', 'TECNICO DE SOPORTE E ITAM' => 'Técnico de Soporte e ITAM',
                'OPERADOR DE TRANSPORTES' => 'Operador de Transportes',
                'PERSONAL GENERAL / REPORTANTE' => 'Personal General / Reportante',
                default => $role,
            };

            $subs = $user['subsystems'] ?? [];
            if (in_array('all', $subs, true)) {
                $subs = ['admin', 'transportes', 'itam', 'helpdesk'];
            }
            $this->userSubsystems = $subs;
            $this->showEditModal = true;
        }
    }

    public function updatedSelectedUserRole(string $role): void
    {
        if ($role === 'Administrador Central') {
            $this->userSubsystems = ['admin', 'transportes', 'itam', 'helpdesk'];
        } elseif ($role === 'Técnico de Soporte e ITAM') {
            $this->userSubsystems = ['admin', 'itam', 'helpdesk'];
        } elseif ($role === 'Operador de Transportes') {
            $this->userSubsystems = ['transportes', 'helpdesk'];
        } elseif ($role === 'Personal General / Reportante') {
            $this->userSubsystems = ['helpdesk'];
        }
    }

    public function toggleSubsystem(string $key): void
    {
        if (in_array('all', $this->userSubsystems, true)) {
            $this->userSubsystems = ['admin', 'transportes', 'itam', 'helpdesk'];
        }

        if (in_array($key, $this->userSubsystems, true)) {
            $this->userSubsystems = array_values(array_filter($this->userSubsystems, fn ($s) => $s !== $key));
        } else {
            $this->userSubsystems[] = $key;
        }
    }

    public function savePermissions(): void
    {
        if ($this->selectedUserId) {
            $dbUser = User::find($this->selectedUserId);
            if ($dbUser) {
                $dbUser->role = $this->selectedUserRole ?: $dbUser->role;
                $dbUser->save();
            }
        }

        $username = $this->selectedUser['username'] ?? 'usuario';
        $this->flashMessage = "Permisos actualizados correctamente para {$username}.";
        $this->showEditModal = false;
    }

    public function openCreateUser(): void
    {
        $this->openCreateUserModal();
    }

    public function openCreateUserModal(): void
    {
        $this->resetUserForm();
        $this->showUserModal = true;
        $this->showCreateModal = true;
    }

    public function closeUserModal(): void
    {
        $this->userModalError = null;
        $this->showUserModal = false;
        $this->showCreateModal = false;
    }

    public function resetUserForm(): void
    {
        $this->userModalError = null;
        $this->userFormPersonId = null;
        $this->userFormFullName = '';
        $this->userFormUsername = '';
        $this->userFormPassword = '';
        $this->userFormRole = 'Técnico de Soporte e ITAM';
        $this->userFormModules = ['admin', 'itam', 'helpdesk'];
    }

    public function updatedUserFormPersonId(): void
    {
        if ($this->userFormPersonId) {
            $person = Person::find($this->userFormPersonId);
            if ($person) {
                $this->userFormFullName = $person->full_name;
                $cleanFirst = strtolower(preg_replace('/[^a-zA-Z]/', '', explode(' ', $person->first_name)[0] ?? ''));
                $cleanLast = strtolower(preg_replace('/[^a-zA-Z]/', '', $person->paternal_surname ?? ''));
                $this->userFormUsername = ($cleanFirst && $cleanLast) ? substr($cleanFirst, 0, 1).$cleanLast : strtolower($person->document_number);
                if (empty($this->userFormPassword)) {
                    $this->userFormPassword = 'password123';
                }
            }
        }
    }

    public function applyRolePreset(string $role): void
    {
        $this->userFormRole = $role;
        $this->userFormModules = match ($role) {
            'Administrador Central' => ['admin', 'transportes', 'itam', 'helpdesk'],
            'Técnico de Soporte e ITAM' => ['admin', 'itam', 'helpdesk'],
            'Operador de Transportes' => ['transportes', 'helpdesk'],
            'Personal General / Reportante' => ['helpdesk'],
            default => ['helpdesk'],
        };
    }

    public function toggleUserModule(string $moduleCode): void
    {
        if (in_array($moduleCode, $this->userFormModules, true)) {
            $this->userFormModules = array_values(array_filter($this->userFormModules, fn ($m) => $m !== $moduleCode));
        } else {
            $this->userFormModules[] = $moduleCode;
        }
    }

    public function saveUser(): void
    {
        $this->userModalError = null;

        if (empty(trim($this->userFormUsername)) || empty(trim($this->userFormFullName))) {
            $this->userModalError = 'El nombre completo y usuario de ingreso son obligatorios.';

            return;
        }

        if (empty($this->userFormPassword) || strlen($this->userFormPassword) < 4) {
            $this->userModalError = 'La contraseña inicial debe tener al menos 4 caracteres.';

            return;
        }

        if (User::where('username', trim($this->userFormUsername))->exists()) {
            $this->userModalError = "El usuario '".trim($this->userFormUsername)."' ya está en uso.";

            return;
        }

        $email = trim($this->userFormUsername).'@castilla.gob.pe';
        if ($this->userFormPersonId) {
            $person = Person::find($this->userFormPersonId);
            if ($person && $person->email) {
                $email = $person->email;
            }
        }

        $user = User::create([
            'username' => trim($this->userFormUsername),
            'full_name' => trim($this->userFormFullName),
            'email' => $email,
            'password' => Hash::make($this->userFormPassword),
            'role' => $this->userFormRole,
            'allowed_modules' => $this->userFormModules,
            'person_id' => $this->userFormPersonId ?: null,
            'is_active' => true,
        ]);

        $this->flashMessage = "Cuenta de usuario '{$user->username}' creada y permisos otorgados exitosamente.";
        $this->showUserModal = false;
        $this->showCreateModal = false;
        $this->resetUserForm();
    }

    public function closeModal(): void
    {
        $this->showEditModal = false;
        $this->showCreateModal = false;
        $this->showUserModal = false;
        $this->selectedUserId = null;
        $this->selectedUser = null;
        $this->userModalError = null;
    }

    public function render(GetUsersCatalogAction $action): View
    {
        $result = $action->handle(
            search: $this->search,
            roleFilter: $this->roleFilter,
            page: $this->page,
            perPage: $this->perPage
        );

        $personnelList = Person::where('type', 'PERSONAL')->where('is_active', true)->orderBy('first_name')->get();

        return view('livewire.admin.users', [
            'users' => $result['items'],
            'total' => $result['total'],
            'currentPage' => $result['currentPage'],
            'perPage' => $result['perPage'],
            'totalPages' => $result['totalPages'],
            'from' => $result['from'],
            'to' => $result['to'],
            'counts' => $result['counts'],
            'presets' => GetUsersCatalogAction::ROLE_PRESETS,
            'personnelList' => $personnelList,
        ]);
    }
}
