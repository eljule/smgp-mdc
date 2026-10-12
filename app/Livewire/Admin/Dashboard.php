<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\Admin\GetAdminDashboardMetricsAction;
use App\Models\Office;
use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.subsystem', [
    'title' => 'Panel de Administración - SIGM Castilla',
    'moduleName' => 'Panel de Administración',
    'subsystem' => 'admin',
    'activeTab' => 'dashboard',
    'counts' => [
        'people' => 46,
        'users' => 49,
        'roles' => 4,
        'permissions' => 19,
    ],
])]
#[Title('Panel de Administración - SIGM Castilla')]
class Dashboard extends Component
{
    public string $activeTab = 'dashboard';

    // Toast Alert
    public ?string $alertMessage = null;

    public string $alertType = 'success';

    // ==========================================
    // 1. MODAL DE REGISTRO DE PERSONA
    // ==========================================
    public bool $showFormModal = false;

    public bool $isEditing = false;

    public ?int $editingPersonId = null;

    public ?string $modalError = null;

    public string $formType = 'PERSONAL';

    public string $formDocumentType = 'DNI';

    public string $formDocumentNumber = '';

    public string $formFirstName = '';

    public string $formPaternalSurname = '';

    public string $formMaternalSurname = '';

    public string $formEmail = '';

    public string $formPhone = '';

    public string $formAddress = '';

    public string $formOffice = '';

    public string $formPosition = '';

    public string $formLaborCondition = 'CAS';

    public string $formEntryDate = '';

    public bool $formAllowsOnlineAccess = false;

    public function openCreateModal(): void
    {
        $this->openCreatePersonModal();
    }

    public function openCreatePersonModal(): void
    {
        $this->resetPersonForm();
        $this->isEditing = false;
        $this->editingPersonId = null;
        $defaultOffice = Office::first();
        if ($defaultOffice) {
            $this->formOffice = "[{$defaultOffice->acronym}] {$defaultOffice->name}";
        }
        $this->showFormModal = true;
    }

    public function resetPersonForm(): void
    {
        $this->modalError = null;
        $this->formType = 'PERSONAL';
        $this->formDocumentType = 'DNI';
        $this->formDocumentNumber = '';
        $this->formFirstName = '';
        $this->formPaternalSurname = '';
        $this->formMaternalSurname = '';
        $this->formEmail = '';
        $this->formPhone = '';
        $this->formAddress = '';
        $this->formOffice = '';
        $this->formPosition = '';
        $this->formLaborCondition = 'CAS';
        $this->formEntryDate = '';
        $this->formAllowsOnlineAccess = false;
    }

    public function closePersonModal(): void
    {
        $this->modalError = null;
        $this->showFormModal = false;
    }

    public function closeModal(): void
    {
        $this->closePersonModal();
        $this->closeUserModal();
    }

    public function savePerson(): void
    {
        $this->modalError = null;

        if (empty(trim($this->formDocumentNumber))) {
            $this->modalError = 'El número de documento es obligatorio.';

            return;
        }

        if ($this->formDocumentType === 'RUC') {
            if (empty(trim($this->formFirstName))) {
                $this->modalError = 'La razón social de la empresa es obligatoria para RUC.';

                return;
            }
            if (empty(trim($this->formPaternalSurname))) {
                $this->formPaternalSurname = 'EMPRESA';
            }
        } else {
            if (empty(trim($this->formFirstName)) || empty(trim($this->formPaternalSurname))) {
                $this->modalError = 'El nombre y apellido paterno son obligatorios.';

                return;
            }
        }

        $data = [
            'type' => $this->formType,
            'document_type' => $this->formDocumentType,
            'document_number' => trim($this->formDocumentNumber),
            'first_name' => mb_strtoupper(trim($this->formFirstName)),
            'paternal_surname' => mb_strtoupper(trim($this->formPaternalSurname)),
            'maternal_surname' => $this->formMaternalSurname ? mb_strtoupper(trim($this->formMaternalSurname)) : null,
            'business_name' => $this->formDocumentType === 'RUC' ? mb_strtoupper(trim($this->formFirstName)) : null,
            'email' => $this->formEmail ? trim($this->formEmail) : null,
            'phone' => $this->formPhone ? trim($this->formPhone) : null,
            'address' => $this->formAddress ? trim($this->formAddress) : null,
            'position' => $this->formType === 'PERSONAL' ? mb_strtoupper(trim($this->formPosition)) : null,
            'office' => $this->formType === 'PERSONAL' ? trim($this->formOffice) : null,
            'labor_condition' => $this->formType === 'PERSONAL' ? $this->formLaborCondition : null,
            'entry_date' => $this->formType === 'PERSONAL' ? ($this->formEntryDate ?: null) : null,
            'allows_online_access' => $this->formType === 'ADMINISTRADO' ? $this->formAllowsOnlineAccess : false,
            'labor_status' => 'ACTIVO',
            'is_active' => true,
        ];

        $person = Person::create($data);
        $this->showAlert("Persona '{$person->full_name}' registrada con éxito.");
        $this->showFormModal = false;
        $this->resetPersonForm();
    }

    // ==========================================
    // 2. MODAL DE CREAR USUARIO Y PERMISOS
    // ==========================================
    public bool $showUserModal = false;

    public ?string $userModalError = null;

    public ?int $userFormPersonId = null;

    public string $userFormFullName = '';

    public string $userFormUsername = '';

    public string $userFormPassword = '';

    public string $userFormRole = 'Técnico de Soporte e ITAM';

    public array $userFormModules = ['admin', 'itam', 'helpdesk'];

    public function openCreateUserModal(): void
    {
        $this->resetUserForm();
        $this->showUserModal = true;
    }

    public function closeUserModal(): void
    {
        $this->userModalError = null;
        $this->showUserModal = false;
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

        $this->showAlert("Usuario '{$user->username}' creado y permisos otorgados con éxito.");
        $this->showUserModal = false;
        $this->resetUserForm();
    }

    public function showAlert(string $message, string $type = 'success'): void
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
    }

    public function render(GetAdminDashboardMetricsAction $action): View
    {
        $metrics = $action->handle();
        $offices = Office::orderBy('name')->get();
        $personnelList = Person::where('type', 'PERSONAL')->where('is_active', true)->orderBy('first_name')->get();

        return view('livewire.admin.dashboard', [
            'peopleCounts' => $metrics['peopleCounts'],
            'usersCount' => $metrics['usersCount'],
            'rolesCount' => $metrics['rolesCount'],
            'permissionsCount' => $metrics['permissionsCount'],
            'subsystems' => $metrics['subsystems'],
            'totalSubsystems' => $metrics['totalSubsystems'],
            'activeSubsystemsCount' => $metrics['activeSubsystemsCount'],
            'offices' => $offices,
            'personnelList' => $personnelList,
        ]);
    }
}
