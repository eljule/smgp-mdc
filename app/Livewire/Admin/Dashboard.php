<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\Admin\GetAdminDashboardMetricsAction;
use App\Enums\ModuleStatus;
use App\Actions\Admin\GetOfficesCatalogAction;
use App\Actions\Admin\GetPeopleCatalogAction;
use App\Actions\Admin\GetPermissionsCatalogAction;
use App\Actions\Admin\GetRolePermissionsMatrixAction;
use App\Actions\Admin\GetRolesCatalogAction;
use App\Actions\Admin\GetSubsystemsCatalogAction;
use App\Actions\Admin\GetUsersCatalogAction;
use App\Models\CoreModule;
use App\Models\Itam\Asset;
use App\Models\Itam\AssetMovement;
use App\Models\Office;
use App\Models\Person;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Permission;
use App\Models\Role;

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
    use WithPagination;

    #[Url(as: 'tab')]
    public string $activeTab = 'dashboard';

    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'pagina')]
    public int $page = 1;

    public int $perPage = 10;

    public ?string $flashMessage = null;
    public string $flashType = 'success';
    public ?string $alertMessage = null;
    public string $alertType = 'success';

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showRoleModal = false;
    public bool $showOfficeModal = false;
    public bool $showSubsystemModal = false;

    public function mount(): void
    {
        if (request()->has('tab')) {
            $this->activeTab = (string) request()->query('tab');
        } else {
            $uri = request()->path();
            if (str_contains($uri, 'personas')) {
                $this->activeTab = 'people';
            } elseif (str_contains($uri, 'usuarios')) {
                $this->activeTab = 'users';
            } elseif (str_contains($uri, 'roles')) {
                $this->activeTab = 'roles';
            } elseif (str_contains($uri, 'asignar-permisos')) {
                $this->activeTab = 'matrix';
            } elseif (str_contains($uri, 'permisos')) {
                $this->activeTab = 'permissions';
            } elseif (str_contains($uri, 'oficinas')) {
                $this->activeTab = 'offices';
            } elseif (str_contains($uri, 'subsistemas')) {
                $this->activeTab = 'subsystems';
            }
        }

        if (request()->has('rol')) {
            $this->selectedRoleCode = (string) request()->query('rol');
        }

        $matrixAction = app(GetRolePermissionsMatrixAction::class);
        $matrixData = $matrixAction->handle();
        $this->assignments = $matrixData['assignments'];
    }

    public function hydrate(): void
    {
        $this->alertMessage = null;
        $this->flashMessage = null;
    }

    public function showAlert(string $message, string $type = 'success'): void
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->flashMessage = $message;
        $this->flashType = $type;
    }

    public function setTab(string $tab): void
    {
        $this->closeModal();
        $this->activeTab = $tab;
        $this->search = '';
        $this->page = 1;
        $this->resetPage();
    }

    public function gotoPage(int $targetPage, string $pageName = 'page'): void
    {
        $this->page = max(1, $targetPage);
        $this->paginators[$pageName] = $this->page;
    }

    public function setPage($page, $pageName = 'page'): void
    {
        $this->page = max(1, (int) $page);
        $this->paginators[$pageName] = $this->page;
    }

    public function previousPage(string $pageName = 'page'): void
    {
        $this->page = max(1, $this->page - 1);
        $this->paginators[$pageName] = $this->page;
    }

    public function nextPage(string $pageName = 'page'): void
    {
        $this->page = $this->page + 1;
        $this->paginators[$pageName] = $this->page;
    }

    public function updatedPerPage(): void
    {
        $this->page = 1;
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->page = 1;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->page = 1;
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->page = 1;
        $this->resetPage();
    }

    public function setFilter(string $filter): void
    {
        if ($this->activeTab === 'subsystems') {
            $this->setSubsystemFilterStatus($filter);
        } else {
            $this->setPersonFilter($filter);
        }
    }

    public function openCreateModal(): void
    {
        match ($this->activeTab) {
            'people' => $this->openCreatePersonModal(),
            'users' => $this->openCreateUserModal(),
            'offices' => $this->openCreateOfficeModal(),
            'roles' => $this->openCreateRoleModal(),
            'subsystems' => $this->openCreateSubsystemModal(),
            default => null,
        };
    }

    public function openEditModal(int $id): void
    {
        match ($this->activeTab) {
            'people' => $this->openEditPersonModal($id),
            'users' => $this->openEditPermissions($id),
            'offices' => $this->openEditOfficeModal($id),
            'roles' => $this->openEditRoleModal($id),
            'subsystems' => $this->openEditSubsystemModal($id),
            default => null,
        };
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showFormModal = false;
        $this->showUserModal = false;
        $this->showRoleModal = false;
        $this->showOfficeModal = false;
        $this->showSubsystemModal = false;
        $this->showCeaseModal = false;
        $this->showCeaseDetailModal = false;
        $this->showSedesModal = false;
        $this->showQrModal = false;
        if (method_exists($this, 'resetPersonForm')) $this->resetPersonForm();
        if (method_exists($this, 'resetUserForm')) $this->resetUserForm();
        if (method_exists($this, 'resetOfficeForm')) $this->resetOfficeForm();
        if (method_exists($this, 'resetRoleForm')) $this->resetRoleForm();
        if (method_exists($this, 'resetSubsystemForm')) $this->resetSubsystemForm();
    }

    public function resetOfficeForm(): void
    {
        $this->selectedOfficeId = null;
        $this->formCode = '';
        $this->formSigla = '';
        $this->formName = '';
        $this->formParentId = '';
        $this->formParentName = '';
        $this->formSede = 'PALACIO MUNICIPAL (SEDE PRINCIPAL)';
        $this->formCustomSede = '';
        $this->isCustomSede = false;
        $this->formIsActive = true;
        $this->formLevel = 1;
        $this->formStatus = 'ACTIVO';
    }

    public function resetRoleForm(): void
    {
        $this->selectedRoleId = null;
        $this->selectedRole = null;
        $this->isEditing = false;
        $this->autoSlug = true;
        $this->formRoleName = '';
        $this->formRoleCode = '';
        $this->formRoleScope = 'global';
        $this->formRoleDescription = '';
    }

    public function resetSubsystemForm(): void
    {
        $this->selectedModuleId = null;
        $this->formCode = '';
        $this->formName = '';
        $this->formDescription = '';
        $this->formRoute = '';
        $this->formOrder = 1;
        $this->formIcon = '⚙️';
        $this->formAccentColorHex = '#00965e';
        $this->formAccentColor = 'emerald';
        $this->formStatus = 'ACTIVO';
        $this->formStatusMessage = '';
        $this->formWaitTime = '';
        $this->formIsActive = true;
        $this->formRequiresAuth = true;
    }

    public string $filterType = 'TODOS';

    public string $officeFilter = '';

    // Modales de formulario y cese
    public bool $showFormModal = false;

    public bool $isEditing = false;

    public ?int $editingPersonId = null;

    public bool $showCeaseModal = false;

    public ?int $ceasePersonId = null;

    public ?array $ceasePersonInfo = null;

    public array $ceaseAssets = [];

    public string $ceaseDepartureDate = '';

    public string $ceaseReason = 'RENUNCIA VOLUNTARIA';

    public string $ceaseCustomReason = '';

    public string $ceaseAuthorizedBy = '';

    public string $ceaseNotes = '';

    public bool $ceaseReturnAssets = true;

    public string $ceaseWarehouseOffice = 'SUBGERENCIA DE TECNOLOGÍAS DE LA INFORMACIÓN Y COMUNICACIONES - ALMACÉN TI';

    public bool $showCeaseDetailModal = false;

    public ?array $ceaseDetailData = null;

    // Campos del formulario Persona
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

    // Alertas Toast
    public ?string $modalError = null;

    

    public function updatedOfficeFilter(): void
    {
        $this->page = 1;
    }

    

    public function clearOfficeFilter(): void
    {
        $this->officeFilter = '';
        $this->page = 1;
    }

    

    

    

    public function setPersonFilter(string $type): void
    {
        $this->filterType = in_array($type, ['TODOS', 'PERSONAL', 'ADMINISTRADO'], true) ? $type : 'TODOS';
        $this->page = 1;
    }

    

    

    

    // ==========================================
    // MODAL DE REGISTRO / EDICIÓN
    // ==========================================
    public function openCreatePersonModal(): void
    {
        $this->resetPersonForm();
        $this->isEditing = false;
        $this->editingPersonId = null;
        $this->formOffice = "";
        $this->showFormModal = true;
    }

    public function openEditPersonModal(int $id): void
    {
        $person = Person::find($id);
        if (! $person) {
            $this->showAlert('Persona no encontrada.', 'error');

            return;
        }

        $this->modalError = null;
        $this->isEditing = true;
        $this->editingPersonId = $id;
        $this->formType = $person->type ?? 'PERSONAL';
        $this->formDocumentType = $person->document_type ?? 'DNI';
        $this->formDocumentNumber = $person->document_number ?? '';
        $this->formFirstName = $person->first_name ?? '';
        $this->formPaternalSurname = $person->paternal_surname ?? '';
        $this->formMaternalSurname = $person->maternal_surname ?? '';
        $this->formEmail = $person->email ?? '';
        $this->formPhone = $person->phone ?? '';
        $this->formAddress = $person->address ?? '';
        $this->formOffice = $person->office ?? '';
        $this->formPosition = $person->position ?? '';
        $this->formLaborCondition = $person->labor_condition ?? 'CAS';
        $this->formEntryDate = $person->entry_date ?? '';
        $this->formAllowsOnlineAccess = (bool) $person->allows_online_access;

        $this->showFormModal = true;
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

        if ($this->isEditing && $this->editingPersonId) {
            $person = Person::find($this->editingPersonId);
            if ($person) {
                $person->update($data);
                $this->showAlert("Persona '{$person->full_name}' actualizada con éxito.");
            }
        } else {
            $person = Person::create($data);
            $this->showAlert("Persona '{$person->full_name}' registrada con éxito.");
        }

        $this->showFormModal = false;
        $this->resetPersonForm();
    }

    // ==========================================
    // MODAL DE CESE LABORAL Y ENTREGA DE CARGO
    // ==========================================
    public function openCeaseModal(int $id): void
    {
        $person = Person::find($id);
        if (! $person) {
            $catalogItem = collect(GetPeopleCatalogAction::PEOPLE_DATA)->firstWhere('id', $id);
            if ($catalogItem && ! empty($catalogItem['documentNumber'])) {
                $person = Person::where('document_number', $catalogItem['documentNumber'])->first();
                if (! $person) {
                    $names = explode(' ', $catalogItem['fullName']);
                    $firstName = $names[0] ?? '';
                    $paternal = $names[1] ?? '';
                    $maternal = count($names) > 2 ? implode(' ', array_slice($names, 2)) : '';
                    $person = Person::create([
                        'type' => $catalogItem['type'] ?? 'PERSONAL',
                        'document_type' => $catalogItem['documentType'] ?? 'DNI',
                        'document_number' => $catalogItem['documentNumber'],
                        'first_name' => $firstName,
                        'paternal_surname' => $paternal,
                        'maternal_surname' => $maternal,
                        'position' => $catalogItem['position'] ?? '',
                        'office' => $catalogItem['office'] ?? '',
                        'labor_condition' => $catalogItem['laborCondition'] ?? 'CAS',
                        'labor_status' => 'ACTIVO',
                        'is_active' => true,
                    ]);
                }
            }
        }

        if (! $person) {
            $this->showAlert('Persona no encontrada.', 'error');

            return;
        }

        if ($person->labor_status === 'CESADO') {
            $this->openCeaseDetailModal($id);

            return;
        }

        $this->ceasePersonId = $id;
        $this->ceaseDepartureDate = date('Y-m-d');
        $this->ceaseReason = 'RENUNCIA VOLUNTARIA';
        $this->ceaseCustomReason = '';
        $this->ceaseAuthorizedBy = '';
        $this->ceaseNotes = '';
        $this->ceaseReturnAssets = true;
        $this->ceaseWarehouseOffice = 'SUBGERENCIA DE TECNOLOGÍAS DE LA INFORMACIÓN Y COMUNICACIONES - ALMACÉN TI';

        $user = User::where('person_id', $person->id)->orWhere('email', $person->email)->first();

        $assets = Asset::with(['category', 'brand', 'model'])
            ->where('assigned_person_id', $person->id)
            ->get();

        $office = $person->office ?? '';
        $officeAcronym = '';
        $officeName = $office;

        if (preg_match('/^\[(.*?)\]\s*(.*)$/', $office, $matches)) {
            $officeAcronym = $matches[1];
            $officeName = $matches[2];
        } elseif ($office) {
            $officeModel = Office::where('name', $office)->first();
            $officeAcronym = $officeModel?->acronym ?? '';
        }

        $this->ceasePersonInfo = [
            'id' => $person->id,
            'fullName' => $person->full_name,
            'documentNumber' => $person->document_number,
            'position' => $person->position,
            'office' => $person->office,
            'officeName' => $officeName,
            'officeAcronym' => $officeAcronym,
            'laborCondition' => $person->labor_condition ?: 'CAS',
            'entryDate' => $person->entry_date,
            'hasUserAccount' => (bool) $user,
            'username' => $user?->username,
        ];

        $this->ceaseAssets = $assets->map(fn (Asset $a) => [
            'id' => $a->id,
            'computerCode' => $a->computer_code,
            'patrimonialCode' => $a->patrimonial_code,
            'categoryName' => $a->category?->name ?? 'Hardware TI',
            'brandModel' => trim(($a->brand?->name ?? '').($a->model?->name ? ' • '.$a->model->name : '')),
            'status' => $a->status,
        ])->toArray();

        $this->showCeaseModal = true;
    }

    public function saveCease(): void
    {
        if (! $this->ceasePersonId) {
            return;
        }

        $person = Person::find($this->ceasePersonId);
        if (! $person) {
            return;
        }

        $reason = $this->ceaseReason === 'OTRO MOTIVO INSTITUCIONAL'
            ? mb_strtoupper(trim($this->ceaseCustomReason))
            : $this->ceaseReason;

        // 1. Actualizar Persona
        $person->update([
            'labor_status' => 'CESADO',
            'is_active' => false,
            'departure_date' => $this->ceaseDepartureDate ?: date('Y-m-d'),
            'cessation_reason' => $reason,
        ]);

        // 2. Desactivar usuario
        $users = User::where('person_id', $person->id)->orWhere('email', $person->email)->get();
        foreach ($users as $u) {
            $u->update(['is_active' => false]);
        }

        // 3. Desvincular activos y retornar a almacén si está marcado
        $returnedCount = 0;
        if ($this->ceaseReturnAssets) {
            $assets = Asset::where('assigned_person_id', $person->id)->get();
            $year = date('Y');
            $baseCount = AssetMovement::count();

            foreach ($assets as $idx => $asset) {
                $actaNum = 'ACTA-CESE-'.$year.'-'.str_pad((string) ($baseCount + $idx + 1), 4, '0', STR_PAD_LEFT);

                AssetMovement::create([
                    'acta_number' => $actaNum,
                    'asset_id' => $asset->id,
                    'from_office' => $asset->office ?: ($person->office ?: 'OFICINA DE ORIGEN'),
                    'to_office' => $this->ceaseWarehouseOffice,
                    'from_person_id' => $person->id,
                    'to_person_id' => null,
                    'movement_date' => $this->ceaseDepartureDate ?: date('Y-m-d'),
                    'movement_type' => 'DEVOLUCION_ALMACEN',
                    'reason' => "ENTREGA DE CARGO POR CESE LABORAL - {$reason}",
                    'technician_name' => $this->ceaseAuthorizedBy ?: 'ADMINISTRACIÓN CENTRAL / TI',
                    'notes' => $this->ceaseNotes ?: 'Recepción y retorno automático a almacén por cese laboral.',
                    'include_children_in_transfer' => true,
                ]);

                $asset->update([
                    'assigned_person_id' => null,
                    'office' => $this->ceaseWarehouseOffice,
                    'status' => 'DISPONIBLE',
                ]);

                $returnedCount++;
            }
        }

        $this->showAlert("Cese laboral registrado para {$person->full_name}. {$returnedCount} activos retornados a almacén.");
        $this->showCeaseModal = false;
    }

    public function openCeaseDetailModal(int $id): void
    {
        $person = Person::find($id);
        if (! $person) {
            $catalogItem = collect(GetPeopleCatalogAction::PEOPLE_DATA)->firstWhere('id', $id);
            if ($catalogItem && ! empty($catalogItem['documentNumber'])) {
                $person = Person::where('document_number', $catalogItem['documentNumber'])->first();
            }
        }

        if (! $person) {
            return;
        }

        $movements = AssetMovement::where('from_person_id', $person->id)
            ->where('movement_type', 'DEVOLUCION_ALMACEN')
            ->with('asset')
            ->get();

        $this->ceaseDetailData = [
            'id' => $person->id,
            'fullName' => $person->full_name,
            'documentNumber' => $person->document_number,
            'position' => $person->position,
            'office' => $person->office,
            'departureDate' => $person->departure_date,
            'cessationReason' => $person->cessation_reason,
            'movements' => $movements->map(fn ($m) => [
                'actaNumber' => $m->acta_number,
                'assetCode' => $m->asset?->computer_code ?? 'ACTIVO',
                'toOffice' => $m->to_office,
                'technician' => $m->technician_name,
                'date' => $m->movement_date,
            ])->toArray(),
        ];

        $this->showCeaseDetailModal = true;
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

    

    

    public string $roleFilter = 'TODOS';

    // Modal de edición de permisos
    

    public ?int $selectedUserId = null;

    public ?array $selectedUser = null;

    public string $selectedUserRole = '';

    public array $userSubsystems = [];

    // Modal interactivo: Crear Usuario y Otorgar Permisos
    public bool $showUserModal = false;

     // Compatibilidad

    public ?string $userModalError = null;

    public ?int $userFormPersonId = null;

    public string $userFormPersonName = '';

    public string $userFormFullName = '';

    public string $userFormUsername = '';

    public string $userFormPassword = '';

    public string $userFormRole = 'Técnico de Soporte e ITAM';

    public array $userFormModules = ['itam', 'helpdesk'];

    public bool $showResetPasswordModal = false;

    public ?int $resetUserId = null;

    public string $resetUsername = '';

    public string $resetFullName = '';

    public string $resetNewPassword = '';

    public ?string $resetPasswordError = null;

    

    

    public function setRoleFilter(string $role): void
    {
        $this->roleFilter = $role;
        $this->page = 1;
    }

    

    

    

    

    public function openEditPermissions(int $userId, ?string $username = null, ?GetUsersCatalogAction $action = null): void
    {
        $this->flashMessage = '';
        $action ??= app(GetUsersCatalogAction::class);

        $user = null;
        if (! empty($username)) {
            $user = $action->findByUsername($username);
        }
        if (! $user) {
            $user = $action->find($userId);
        }

        if ($user) {
            $this->selectedUserId = $user['dbId'] ?? $user['id'];
            $this->selectedUser = $user;

            $dbUser = null;
            if (! empty($username)) {
                $dbUser = User::where('username', $username)->first();
            }
            if (! $dbUser && $this->selectedUserId) {
                $dbUser = User::find($this->selectedUserId);
            }

            if ($dbUser) {
                $role = $dbUser->role ?: ($user['role'] ?? $user['primaryRole'] ?? 'Administrador Central');
                $subs = (array) ($dbUser->allowed_modules ?? ($user['subsystems'] ?? []));
            } else {
                $role = $user['role'] ?? $user['primaryRole'] ?? 'Administrador Central';
                $subs = $user['subsystems'] ?? [];
            }

            $this->selectedUserRole = match (mb_strtoupper($role)) {
                'ADMINISTRADOR CENTRAL' => 'Administrador Central',
                'TÉCNICO DE SOPORTE E ITAM', 'TECNICO DE SOPORTE E ITAM' => 'Técnico de Soporte e ITAM',
                'OPERADOR DE TRANSPORTES' => 'Operador de Transportes',
                'PERSONAL GENERAL / REPORTANTE' => 'Personal General / Reportante',
                default => $role,
            };

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
            $this->userSubsystems = ['itam', 'helpdesk'];
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
        $username = $this->selectedUser['username'] ?? null;
        $dbUser = null;
        if (! empty($username)) {
            $dbUser = User::where('username', $username)->first();
        }
        if (! $dbUser && $this->selectedUserId) {
            $dbUser = User::find($this->selectedUserId);
        }

        if ($dbUser) {
            $dbUser->role = $this->selectedUserRole ?: $dbUser->role;
            $dbUser->allowed_modules = array_values($this->userSubsystems);
            $dbUser->save();
        }

        $usernameStr = $username ?? 'usuario';
        $this->flashMessage = "Permisos actualizados correctamente para {$usernameStr}.";
        $this->showEditModal = false;
    }

    public function openCreateUser(): void
    {
        $this->flashMessage = '';
        $this->openCreateUserModal();
    }

    public function openCreateUserModal(): void
    {
        $this->flashMessage = '';
        $this->resetUserForm();
        $this->showUserModal = true;
        $this->showCreateModal = false;
    }

    public function closeUserModal(): void
    {
        $this->flashMessage = '';
        $this->userModalError = null;
        $this->showUserModal = false;
        $this->showRoleModal = false;
        $this->showOfficeModal = false;
        $this->showSubsystemModal = false;
        $this->showCreateModal = false;
    }

    public function resetUserForm(): void
    {
        $this->userModalError = null;
        $this->userFormPersonId = null;
        $this->userFormPersonName = '';
        $this->userFormFullName = '';
        $this->userFormUsername = '';
        $this->userFormPassword = '';
        $this->userFormRole = 'Técnico de Soporte e ITAM';
        $this->userFormModules = ['itam', 'helpdesk'];
    }

    public function updatedUserFormPersonName(string $value): void
    {
        $this->resolveUserFormPerson($value);
    }

    protected function resolveUserFormPerson(?string $value): void
    {
        $clean = trim((string) $value);

        if ($clean === '' || str_contains(mb_strtolower($clean), 'sin servidor') || str_contains(mb_strtolower($clean), 'sin custodio')) {
            $this->userFormPersonId = null;

            return;
        }

        $person = null;

        // 1. Extraer documento si viene en formato "... (45678912)" o "[45678912]"
        if (preg_match('/\((\d{8,11})\)/', $clean, $matches) || preg_match('/\[(\d{8,11})\]/', $clean, $matches)) {
            $person = Person::where('document_number', $matches[1])->first();
        }

        // 2. Si se ingresó directamente un documento de identidad (DNI/RUC)
        if (! $person && ctype_digit($clean)) {
            $person = Person::where('document_number', $clean)->first();
        }

        // 3. Coincidencia por nombre completo
        if (! $person) {
            $cleanUpper = mb_strtoupper($clean);
            $person = Person::where('type', 'PERSONAL')->get()->first(function ($p) use ($cleanUpper) {
                $fullName = mb_strtoupper($p->full_name);

                return $fullName === $cleanUpper
                    || str_contains($fullName, $cleanUpper)
                    || str_contains($cleanUpper, $fullName);
            });
        }

        if ($person) {
            $this->userFormPersonId = $person->id;
            $this->userFormFullName = $person->full_name;
            $cleanFirst = strtolower(preg_replace('/[^a-zA-Z]/', '', explode(' ', $person->first_name)[0] ?? ''));
            $cleanLast = strtolower(preg_replace('/[^a-zA-Z]/', '', $person->paternal_surname ?? ''));
            $this->userFormUsername = ($cleanFirst && $cleanLast) ? substr($cleanFirst, 0, 1).$cleanLast : strtolower($person->document_number);
            if (empty($this->userFormPassword)) {
                $this->userFormPassword = 'password123';
            }
        } else {
            $this->userFormPersonId = null;
        }
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
            'Técnico de Soporte e ITAM' => ['itam', 'helpdesk'],
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

    public function openResetPassword(int $userId, ?string $username = null, ?GetUsersCatalogAction $action = null): void
    {
        $this->flashMessage = '';
        $this->resetPasswordError = null;
        $action ??= app(GetUsersCatalogAction::class);

        $dbUser = null;
        if (! empty($username)) {
            $dbUser = User::where('username', $username)->first();
        }

        if (! $dbUser) {
            $catalogUser = $action->find($userId);
            if (! empty($catalogUser['username'])) {
                $dbUser = User::where('username', $catalogUser['username'])->first();
            }
            if (! $dbUser && ! empty($catalogUser['dbId'])) {
                $dbUser = User::find($catalogUser['dbId']);
            }
        }

        if (! $dbUser) {
            $dbUser = User::find($userId);
        }

        if ($dbUser) {
            $this->resetUserId = $dbUser->id;
            $this->resetUsername = $dbUser->username;
            $this->resetFullName = $dbUser->display_title ?: $dbUser->full_name;
            $this->resetNewPassword = 'Mdc'.random_int(1000, 9999).'!';
            $this->showResetPasswordModal = true;

            return;
        }

        $catalogUser ??= $action->find($userId);
        if ($catalogUser) {
            $this->resetUserId = $catalogUser['dbId'] ?? $catalogUser['id'];
            $this->resetUsername = (string) ($catalogUser['username'] ?? '');
            $this->resetFullName = (string) ($catalogUser['fullName'] ?? $this->resetUsername);
            $this->resetNewPassword = 'Mdc'.random_int(1000, 9999).'!';
            $this->showResetPasswordModal = true;
        }
    }

    public function generateRandomPassword(): void
    {
        $this->resetNewPassword = 'Mdc'.random_int(1000, 9999).'!';
    }

    public function closeResetPasswordModal(): void
    {
        $this->showResetPasswordModal = false;
        $this->resetUserId = null;
        $this->resetPasswordError = null;
    }

    public function executeResetPassword(): void
    {
        $this->resetPasswordError = null;
        $pwd = trim($this->resetNewPassword);
        if (empty($pwd) || strlen($pwd) < 6) {
            $this->resetPasswordError = 'La nueva contraseña provisional debe tener al menos 6 caracteres.';

            return;
        }

        $user = User::where('username', $this->resetUsername)->first();
        if (! $user) {
            $action = app(GetUsersCatalogAction::class);
            $catalogUser = $action->find($this->resetUserId ?? 0);
            if ($catalogUser) {
                $user = User::create([
                    'username' => $catalogUser['username'],
                    'full_name' => $catalogUser['fullName'],
                    'email' => $catalogUser['email'] ?? ($catalogUser['username'].'@castilla.gob.pe'),
                    'password' => Hash::make($pwd),
                    'role' => $catalogUser['primaryRole'] ?? 'Usuario Municipal',
                    'allowed_modules' => $catalogUser['subsystems'] ?? ['helpdesk'],
                    'person_id' => $catalogUser['personId'] ?? null,
                    'is_active' => true,
                    'must_change_password' => true,
                    'failed_login_attempts' => 0,
                    'is_locked' => false,
                ]);
            }
        }

        if ($user) {
            $user->password = Hash::make($pwd);
            $user->must_change_password = true;
            $user->is_locked = false;
            $user->locked_at = null;
            $user->failed_login_attempts = 0;
            $user->save();

            $this->flashMessage = "Contraseña provisional asignada exitosamente a {$user->username}. Clave temporal: {$pwd}. El usuario deberá cambiarla obligatoriamente al ingresar.";
            $this->showResetPasswordModal = false;
        } else {
            $this->resetPasswordError = 'No se pudo localizar el registro del usuario.';
        }
    }

    public function unlockUser(int $userId, ?string $username = null, ?GetUsersCatalogAction $action = null): void
    {
        $action ??= app(GetUsersCatalogAction::class);
        $user = null;

        if (! empty($username)) {
            $user = User::where('username', $username)->first();
        }

        if (! $user) {
            $catalogUser = $action->find($userId);
            if (! empty($catalogUser['username'])) {
                $user = User::where('username', $catalogUser['username'])->first();
            }
            if (! $user && ! empty($catalogUser['dbId'])) {
                $user = User::find($catalogUser['dbId']);
            }
        }

        if (! $user) {
            $user = User::find($userId);
        }

        if ($user) {
            $user->unlock();
            $this->flashMessage = "La cuenta de '{$user->username}' ha sido desbloqueada correctamente.";
        } else {
            $this->flashMessage = "No se pudo localizar el usuario para desbloquear.";
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

        if (! $this->userFormPersonId && ! empty($this->userFormPersonName)) {
            $this->resolveUserFormPerson($this->userFormPersonName);
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
            'must_change_password' => true,
            'failed_login_attempts' => 0,
            'is_locked' => false,
        ]);

        $this->flashMessage = "Cuenta de usuario '{$user->username}' creada y permisos otorgados exitosamente.";
        $this->showUserModal = false;
        $this->showRoleModal = false;
        $this->showOfficeModal = false;
        $this->showSubsystemModal = false;
        $this->showCreateModal = false;
        $this->resetUserForm();
    }

    

    

    public string $sedeFilter = 'TODAS';

    public string $levelFilter = 'TODOS';

    public string $viewMode = 'table';

    

    

    public ?int $selectedOfficeId = null;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $selectedOffice = null;

    // Form properties for office modal
    public string $formCode = '';

    public string $formSigla = '';

    public string $formName = '';

    public mixed $formParentId = null;

    public string $formParentName = '';

    public string $formSede = 'PALACIO MUNICIPAL (SEDE PRINCIPAL)';

    public string $formCustomSede = '';

    public bool $isCustomSede = false;

    public bool $formIsActive = true;

    public int $formLevel = 2;

    public string $formStatus = 'Activo';

    

    // ==========================================
    // PROPIEDADES PARA MODAL DE CÓDIGO QR DE OFICINA
    // ==========================================
    public bool $showQrModal = false;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $selectedOfficeForQr = null;

    

    // ==========================================
    // PROPIEDADES PARA GESTIÓN DE SEDES Y COLORES
    // ==========================================
    public bool $showSedesModal = false;

    public bool $showSedeForm = false;

    public ?int $selectedSedeId = null;

    public string $sedeFormName = '';

    public string $sedeFormCode = '';

    public string $sedeFormAddress = '';

    public string $sedeFormColor = 'emerald';

    public bool $sedeFormIsActive = true;

    public string $sedeErrorMessage = '';

    

    public function updatedSedeFilter(): void
    {
        $this->page = 1;
    }

    public function updatedLevelFilter(): void
    {
        $this->page = 1;
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['table', 'chart'], true) ? $mode : 'table';
    }

    

    public function resetFilters(): void
    {
        $this->search = '';
        $this->sedeFilter = 'TODAS';
        $this->levelFilter = 'TODOS';
        $this->page = 1;
    }

    public function onSedeChange(string $val): void
    {
        if ($val === 'OTRA') {
            $this->isCustomSede = true;
            $this->formCustomSede = '';
        } else {
            $this->formSede = $val;
            $this->isCustomSede = false;
        }
    }

    public function updatedFormParentId(mixed $val): void
    {
        if ($val === '' || $val === null || $val === '0' || $val === 0) {
            $this->formParentId = null;
            $this->formParentName = '';
            $this->formLevel = 1;

            return;
        }

        $parentId = (int) $val;
        $this->formParentId = $parentId;
        $parent = Office::find($parentId);
        if ($parent) {
            $this->formParentName = "{$parent->code} - [{$parent->acronym}] {$parent->name}";
            $this->formLevel = min(4, ((int) $parent->level) + 1);
        } else {
            $this->formParentName = '';
            $this->formLevel = 1;
        }
    }

    public function updatedFormParentName(mixed $value): void
    {
        $this->resolveFormParent((string) $value);
    }

    public function resolveFormParent(?string $value): void
    {
        $clean = trim((string) $value);

        if ($clean === '' || str_contains(mb_strtolower($clean), 'sin dependencia') || str_contains(mb_strtolower($clean), 'ninguna')) {
            $this->formParentId = null;
            $this->formParentName = '';
            $this->formLevel = 1;

            return;
        }

        $parent = null;

        // 1. Extraer código inicial antes del guion: "02 - [ALC] ..."
        if (preg_match('/^([0-9\.]+)\s*-/i', $clean, $matches)) {
            $parent = Office::where('code', trim($matches[1]))->first();
        }

        // 2. Extraer sigla dentro de corchetes: "[ALC]"
        if (! $parent && preg_match('/\[([A-Za-z0-9\.\-_]+)\]/i', $clean, $matches)) {
            $parent = Office::where('acronym', trim($matches[1]))->first();
        }

        // 3. Buscar por código exacto directo (ej. "02" o "02.01")
        if (! $parent) {
            $parent = Office::where('code', $clean)->first();
        }

        // 4. Buscar por sigla directa
        if (! $parent) {
            $parent = Office::whereRaw('LOWER(acronym) = ?', [mb_strtolower($clean)])->first();
        }

        // 5. Buscar por nombre exacto
        if (! $parent) {
            $parent = Office::whereRaw('LOWER(name) = ?', [mb_strtolower($clean)])->first();
        }

        if ($parent) {
            if ($this->selectedOfficeId && (int) $parent->id === (int) $this->selectedOfficeId) {
                $this->addError('formParentName', 'Una dependencia no puede ser su propia dependencia superior.');
                $this->formParentId = null;

                return;
            }

            $this->formParentId = (int) $parent->id;
            $this->formParentName = "{$parent->code} - [{$parent->acronym}] {$parent->name}";
            $this->formLevel = min(4, ((int) $parent->level) + 1);
        } else {
            $this->formParentId = null;
            $this->formLevel = 1;
        }
    }

    public function openCreateOfficeModal(): void
    {
        $this->resetValidation();
        $this->formCode = '';
        $this->formSigla = '';
        $this->formName = '';
        $this->formParentId = null;
        $this->formParentName = '';
        $this->formSede = 'PALACIO MUNICIPAL (SEDE PRINCIPAL)';
        $this->formCustomSede = '';
        $this->isCustomSede = false;
        $this->formIsActive = true;
        $this->formLevel = 2;
        $this->formStatus = 'Activo';
        $this->selectedOfficeId = null;
        $this->selectedOffice = null;
        $this->showEditModal = false;
        $this->showOfficeModal = true;
        $this->showCreateModal = true;
    }

    public function openEditOfficeModal(int $id, ?GetOfficesCatalogAction $action = null): void
    {
        $action ??= app(GetOfficesCatalogAction::class);
        $this->resetValidation();
        $office = Office::with('parent')->find($id);

        if ($office) {
            $this->selectedOfficeId = $office->id;
            $this->selectedOffice = [
                'id' => $office->id,
                'code' => $office->code,
                'sigla' => $office->acronym,
                'name' => $office->name,
                'parentCode' => $office->parent?->code,
                'parentName' => $office->parent?->name ?? $office->parent_name,
                'isSuperior' => $office->level === 1,
                'sede' => $office->sede,
                'level' => $office->level,
                'levelLabel' => 'Nivel '.$office->level,
                'status' => $office->is_active ? 'Activo' : 'Inactivo',
                'isActive' => (bool) $office->is_active,
            ];

            $this->formCode = (string) $office->code;
            $this->formSigla = (string) $office->acronym;
            $this->formName = (string) $office->name;
            $this->formParentId = $office->parent_id ? (int) $office->parent_id : null;
            $this->formParentName = $office->parent ? "{$office->parent->code} - [{$office->parent->acronym}] {$office->parent->name}" : (string) ($office->parent_name ?? '');
            $this->formLevel = (int) ($office->level ?? 1);
            $this->formIsActive = (bool) $office->is_active;
            $this->formStatus = $office->is_active ? 'Activo' : 'Inactivo';

            $sedesList = $action->getSedes();
            if (in_array($office->sede, $sedesList, true)) {
                $this->formSede = (string) $office->sede;
                $this->isCustomSede = false;
                $this->formCustomSede = '';
            } else {
                $this->formSede = 'OTRA';
                $this->isCustomSede = true;
                $this->formCustomSede = (string) $office->sede;
            }

            $this->showCreateModal = false;
            $this->showOfficeModal = true;
            $this->showEditModal = true;
        }
    }

    

    public function saveOffice(): void
    {
        $officeId = $this->showEditModal ? $this->selectedOfficeId : null;

        $this->validate([
            'formCode' => [
                'required',
                'string',
                'max:30',
                $officeId
                    ? Rule::unique('core_offices', 'code')->ignore($officeId)
                    : Rule::unique('core_offices', 'code'),
            ],
            'formSigla' => ['required', 'string', 'max:30'],
            'formName' => ['required', 'string', 'max:255'],
            'formParentId' => ['nullable'],
            'formSede' => $this->isCustomSede ? ['nullable'] : ['required', 'string'],
            'formCustomSede' => $this->isCustomSede ? ['required', 'string', 'max:120'] : ['nullable'],
        ], [
            'formCode.required' => 'El código orgánico es obligatorio.',
            'formCode.unique' => 'El código orgánico ya está registrado en otra dependencia.',
            'formSigla.required' => 'La sigla oficial es obligatoria.',
            'formName.required' => 'El nombre completo de la dependencia es obligatorio.',
            'formCustomSede.required' => 'Debe ingresar el nombre de la sede municipal.',
        ]);

        // Prevent circular assignment
        if ($officeId && ! empty($this->formParentId) && (int) $this->formParentId === (int) $officeId) {
            $this->addError('formParentId', 'Una dependencia no puede ser su propia dependencia superior.');

            return;
        }

        $finalSede = $this->isCustomSede ? trim($this->formCustomSede) : $this->formSede;
        if ($finalSede === '' || $finalSede === 'OTRA') {
            $finalSede = 'PALACIO MUNICIPAL (SEDE PRINCIPAL)';
        }

        if (! Sede::where('name', $finalSede)->exists()) {
            Sede::create([
                'name' => $finalSede,
                'code' => 'SEDE-'.strtoupper(substr((string) preg_replace('/[^A-Za-z0-9]/', '', $finalSede), 0, 10)),
                'color' => 'slate',
                'is_active' => true,
            ]);
            Sede::clearColorCache();
        }

        if (empty($this->formParentId) && ! empty($this->formParentName)) { $this->resolveFormParent($this->formParentName); }
        $parentId = (! empty($this->formParentId)) ? (int) $this->formParentId : null;
        $parentOffice = $parentId ? Office::find($parentId) : null;
        $parentName = $parentOffice ? $parentOffice->name : null;
        $calculatedLevel = $parentOffice ? (((int) $parentOffice->level) + 1) : 1;

        $isEdit = $this->showEditModal && $officeId;
        $office = $isEdit ? Office::find($officeId) : new Office;

        if (! $office) {
            $office = new Office;
        }

        $oldName = $office->name;

        $office->code = trim($this->formCode);
        $office->acronym = strtoupper(trim($this->formSigla));
        $office->name = strtoupper(trim($this->formName));
        $office->parent_id = $parentOffice?->id;
        $office->parent_name = $parentName;
        $office->sede = $finalSede;
        $office->level = $calculatedLevel;
        $office->is_active = (bool) $this->formIsActive;
        $office->save();

        if ($isEdit && $oldName && $oldName !== $office->name) {
            Office::where('parent_id', $office->id)->update([
                'parent_name' => $office->name,
            ]);
        }

        $name = $office->name;
        $this->closeModal();

        $this->flashMessage = $isEdit ? "Dependencia '{$name}' actualizada con éxito en la estructura orgánica." : "Dependencia '{$name}' registrada con éxito en la estructura orgánica.";
    }

    public function deleteOffice(int $id): void
    {
        $office = Office::find($id);
        if ($office) {
            $name = $office->name;
            Office::where('parent_id', $office->id)->update([
                'parent_id' => $office->parent_id,
                'parent_name' => $office->parent_name,
            ]);
            $office->delete();

            $this->flashMessage = "La dependencia '{$name}' ha sido eliminada con éxito.";
        }
    }

    // ==========================================
    // MÉTODOS CRUD PARA GESTIÓN DE SEDES Y COLORES
    // ==========================================

    public function openSedesModal(): void
    {
        $this->showSedesModal = true;
        $this->showSedeForm = false;
        $this->selectedSedeId = null;
        $this->sedeErrorMessage = '';
    }

    public function closeSedesModal(): void
    {
        $this->showSedesModal = false;
        $this->showSedeForm = false;
        $this->selectedSedeId = null;
        $this->sedeErrorMessage = '';
    }

    public function openCreateSedeForm(): void
    {
        $this->selectedSedeId = null;
        $this->sedeFormName = '';
        $this->sedeFormCode = '';
        $this->sedeFormAddress = '';
        $this->sedeFormColor = 'emerald';
        $this->sedeFormIsActive = true;
        $this->sedeErrorMessage = '';
        $this->showSedeForm = true;
    }

    public function openEditSedeForm(int $id): void
    {
        $sede = Sede::find($id);
        if ($sede) {
            $this->selectedSedeId = $sede->id;
            $this->sedeFormName = $sede->name;
            $this->sedeFormCode = $sede->code ?? '';
            $this->sedeFormAddress = $sede->address ?? '';
            $this->sedeFormColor = $sede->color;
            $this->sedeFormIsActive = (bool) $sede->is_active;
            $this->sedeErrorMessage = '';
            $this->showSedeForm = true;
        }
    }

    public function cancelSedeForm(): void
    {
        $this->showSedeForm = false;
        $this->selectedSedeId = null;
        $this->sedeErrorMessage = '';
    }

    public function setSedeColor(string $color): void
    {
        if (array_key_exists($color, Sede::COLOR_PRESETS)) {
            $this->sedeFormColor = $color;
        }
    }

    public function saveSede(): void
    {
        $this->sedeErrorMessage = '';
        $trimmedName = trim($this->sedeFormName);

        if (empty($trimmedName)) {
            $this->sedeErrorMessage = 'El nombre de la sede es obligatorio.';

            return;
        }

        $query = Sede::where('name', $trimmedName);
        if ($this->selectedSedeId) {
            $query->where('id', '!=', $this->selectedSedeId);
        }
        if ($query->exists()) {
            $this->sedeErrorMessage = 'Ya existe una sede registrada con ese nombre.';

            return;
        }

        $color = array_key_exists($this->sedeFormColor, Sede::COLOR_PRESETS)
            ? $this->sedeFormColor
            : 'emerald';

        if ($this->selectedSedeId) {
            $sede = Sede::findOrFail($this->selectedSedeId);
            $oldName = $sede->name;

            $sede->update([
                'name' => $trimmedName,
                'code' => trim($this->sedeFormCode) ?: null,
                'address' => trim($this->sedeFormAddress) ?: null,
                'color' => $color,
                'is_active' => $this->sedeFormIsActive,
            ]);

            if ($oldName !== $trimmedName) {
                Office::where('sede', $oldName)->update(['sede' => $trimmedName]);
            }

            Sede::clearColorCache();
            $this->flashMessage = "Sede '{$trimmedName}' actualizada correctamente con el color configurado.";
        } else {
            $code = trim($this->sedeFormCode) ?: null;

            Sede::create([
                'name' => $trimmedName,
                'code' => $code,
                'address' => trim($this->sedeFormAddress) ?: null,
                'color' => $color,
                'is_active' => $this->sedeFormIsActive,
            ]);

            Sede::clearColorCache();
            $this->flashMessage = "Nueva sede '{$trimmedName}' registrada con éxito en el catálogo municipal.";
        }

        $this->showSedeForm = false;
        $this->selectedSedeId = null;
    }

    public function deleteSede(int $id): void
    {
        $sede = Sede::find($id);
        if ($sede) {
            $name = $sede->name;
            $affectedCount = Office::where('sede', $name)->count();

            if ($affectedCount > 0) {
                Office::where('sede', $name)->update(['sede' => 'PALACIO MUNICIPAL (SEDE PRINCIPAL)']);
            }

            $sede->delete();
            Sede::clearColorCache();

            $reassignMsg = $affectedCount > 0
                ? " ({$affectedCount} dependencias reasignadas a Sede Principal)"
                : '';
            $this->flashMessage = "La sede '{$name}' ha sido eliminada{$reassignMsg}.";
        }
    }

    // ==========================================
    // MÉTODOS PARA MODAL DE CÓDIGO QR DE OFICINA
    // ==========================================

    public function openQrModal(int $id, ?GetOfficesCatalogAction $action = null): void
    {
        $action ??= new GetOfficesCatalogAction;
        $all = $action->execute();

        foreach ($all as $office) {
            if ($office['id'] === $id) {
                $ticketUrl = route('soporte.nuevo', [
                    'oficina' => $office['name'],
                ]);

                $this->selectedOfficeForQr = array_merge($office, [
                    'ticketUrl' => $ticketUrl,
                    'qrUrl' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($ticketUrl),
                ]);
                $this->showQrModal = true;

                return;
            }
        }
    }

    public function closeQrModal(): void
    {
        $this->showQrModal = false;
        $this->selectedOfficeForQr = null;
    }

    

    public string $scopeFilter = 'TODOS';

    

    

    

    public bool $autoSlug = true;

    public ?int $selectedRoleId = null;

    public ?array $selectedRole = null;

    // Campos formulario de Rol
    public string $formRoleName = '';

    public string $formRoleCode = '';

    public string $formRoleScope = 'global';

    public string $formRoleDescription = '';

    

    

    public function setScopeFilter(string $scope): void
    {
        $this->scopeFilter = $scope;
        $this->page = 1;
    }

    

    

    

    public function updatedFormRoleName(string $val): void
    {
        if ($this->autoSlug && ! $this->isEditing) {
            $slug = mb_strtolower(trim($val));
            $slug = preg_replace('/[^\p{L}\p{N}]+/u', '_', $slug);
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', (string) $slug) ?: $slug;
            $slug = preg_replace('/[^a-z0-9_]/', '', (string) $slug);
            $this->formRoleCode = trim((string) $slug, '_');
        }
    }

    public function openCreateRoleModal(): void
    {
        $this->isEditing = false;
        $this->autoSlug = true;
        $this->formRoleName = '';
        $this->formRoleCode = '';
        $this->formRoleScope = 'global';
        $this->formRoleDescription = '';
        $this->showRoleModal = true;
        $this->showCreateModal = true;
    }

    public function goToMatrix(string $roleCode): void
    {
        $this->redirect(route('admin.matrix', ['rol' => $roleCode]), navigate: true);
    }

    public function openEditRoleModal(int $roleId, ?GetRolesCatalogAction $action = null): void
    {
        $action ??= app(GetRolesCatalogAction::class);
        $role = $action->find($roleId);

        if ($role) {
            $this->isEditing = true;
            $this->autoSlug = false;
            $this->selectedRoleId = $roleId;
            $this->selectedRole = $role;
            $this->formRoleName = $role['name'];
            $this->formRoleCode = $role['code'];
            $this->formRoleScope = $role['scopeKey'] ?? 'global';
            $this->formRoleDescription = $role['description'] ?? '';
            $this->showRoleModal = true;
            $this->showCreateModal = true;
            $this->showEditModal = true;
        }
    }

    

    public function saveRole(): void
    {
        if (empty(trim($this->formRoleName))) {
            $this->formRoleName = 'Nuevo Rol Institucional';
            $this->formRoleCode = 'nuevo_rol_'.time();
        }

        $this->flashMessage = 'Rol institucional guardado correctamente.';
        $this->closeModal();
    }

    public function deleteRole(int $roleId, ?GetRolesCatalogAction $action = null): void
    {
        $action ??= app(GetRolesCatalogAction::class);
        $role = $action->find($roleId);

        if ($role && ! $role['isSystem']) {
            $this->flashMessage = "El rol '{$role['name']}' ha sido eliminado con éxito.";
        } else {
            $this->flashMessage = 'No se puede eliminar un rol base protegido del sistema.';
        }
    }

    

    public string $subsystemFilter = 'TODOS';

    /**
     * Filter by subsystem key.
     */
    public function setSubsystemFilter(string $subsystem): void
    {
        $this->subsystemFilter = $subsystem;
    }

    /**
     * Clear search.
     */
    

    /**
     * Render the permissions catalog view.
     */
    

    public string $selectedRoleCode = 'admin_central';

    /**
     * @var array<string, array<int, string>>
     */
    public array $assignments = [];

    

    

    public bool $hasChanges = false;

    /**
     * Initialize assignments with catalog defaults.
     */
    

    /**
     * Switch active role.
     */
    public function selectRole(string $code): void
    {
        $this->selectedRoleCode = $code;
    }

    /**
     * Toggle a single permission for the active role.
     */
    public function togglePermission(string $permCode): void
    {
        $current = $this->assignments[$this->selectedRoleCode] ?? [];

        if (in_array($permCode, $current, true)) {
            $this->assignments[$this->selectedRoleCode] = array_values(array_filter($current, fn ($p) => $p !== $permCode));
        } else {
            $current[] = $permCode;
            $this->assignments[$this->selectedRoleCode] = array_values($current);
        }

        $this->hasChanges = true;
    }

    /**
     * Enable all permissions in a subsystem.
     *
     * @param  array<int, string>  $permCodes
     */
    public function enableSubsystem(array $permCodes): void
    {
        $current = $this->assignments[$this->selectedRoleCode] ?? [];
        $merged = array_unique(array_merge($current, $permCodes));
        $this->assignments[$this->selectedRoleCode] = array_values($merged);
        $this->hasChanges = true;
    }

    /**
     * Disable all permissions in a subsystem.
     *
     * @param  array<int, string>  $permCodes
     */
    public function disableSubsystem(array $permCodes): void
    {
        $current = $this->assignments[$this->selectedRoleCode] ?? [];
        $this->assignments[$this->selectedRoleCode] = array_values(array_diff($current, $permCodes));
        $this->hasChanges = true;
    }

    /**
     * Mark all 19 permissions active for current role.
     *
     * @param  array<int, string>  $allCodes
     */
    public function selectAll(array $allCodes): void
    {
        $this->assignments[$this->selectedRoleCode] = $allCodes;
        $this->hasChanges = true;
    }

    /**
     * Deselect all permissions for current role.
     */
    public function deselectAll(): void
    {
        $this->assignments[$this->selectedRoleCode] = [];
        $this->hasChanges = true;
    }

    /**
     * Save matrix changes.
     */
    public function saveMatrix(): void
    {
        $catalogRoles = GetRolePermissionsMatrixAction::ROLES;

        foreach ($this->assignments as $roleCode => $perms) {
            $roleModel = Role::where('code', $roleCode)->first();
            if ($roleModel) {
                $roleModel->permission_codes = array_values(array_unique($perms));
                $roleModel->save();
            } else {
                $catalogRole = $catalogRoles[$roleCode] ?? null;
                if ($catalogRole) {
                    Role::create([
                        'code' => $roleCode,
                        'name' => $catalogRole['name'],
                        'description' => $catalogRole['description'] ?? '',
                        'subsystem_code' => $roleCode === 'admin_central' ? 'GLOBAL' : $roleCode,
                        'is_system' => (bool) ($catalogRole['isSystem'] ?? false),
                        'permission_codes' => array_values(array_unique($perms)),
                        'is_active' => true,
                    ]);
                }
            }
        }

        $this->hasChanges = false;
        $this->flashMessage = 'Permisos del rol actualizados correctamente.';
    }

    /**
     * Reset unsaved changes to defaults.
     */
    public function resetMatrix(?GetRolePermissionsMatrixAction $matrixAction = null): void
    {
        $matrixAction ??= app(GetRolePermissionsMatrixAction::class);
        $this->assignments = $matrixAction->handle()['assignments'];
        $this->hasChanges = false;
        $this->flashMessage = 'Cambios descartados y permisos restablecidos.';
    }

    /**
     * Render the matrix view.
     */
    

    public string $filter = 'todos';

    

    

    public ?int $selectedModuleId = null;

    // Form fields
    

    

    public string $formDescription = '';

    public string $formRoute = '/';

    public int $formOrder = 5;

    public string $formIcon = '🏢';

    public string $formAccentColorHex = '#16a34a';

    public string $formAccentColor = 'emerald';

    

    

    public string $formWaitTime = '';

    

    public bool $formRequiresAuth = true;

    

    

    

    public function setSubsystemFilterStatus(string $filter): void
    {
        $this->filter = in_array($filter, ['todos', 'operativos', 'mantenimiento', 'inactivos'], true)
            ? $filter
            : 'todos';
    }

    public function selectAccentColor(string $hex): void
    {
        $this->formAccentColorHex = $hex;
        $this->formAccentColor = match ($hex) {
            '#ea580c', '#dc2626', '#d97706' => 'amber',
            '#2563eb', '#0891b2' => 'blue',
            '#7c3aed' => 'purple',
            default => 'emerald',
        };
    }

    public function toggleMaintenance(int $moduleId): void
    {
        $module = CoreModule::find($moduleId);

        if ($module) {
            if ($module->status === ModuleStatus::Maintenance) {
                $module->status = ModuleStatus::Active;
                $module->status_message = null;
                $module->wait_time = null;
                $this->flashMessage = "El subsistema '{$module->name}' ahora está OPERATIVO.";
            } else {
                $module->status = ModuleStatus::Maintenance;
                $module->status_message = 'Este subsistema se encuentra temporalmente suspendido por trabajos de mantenimiento y optimización.';
                $module->wait_time = 'Hasta nuevo aviso';
                $this->flashMessage = "El subsistema '{$module->name}' ha entrado en modo MANTENIMIENTO.";
            }

            $module->save();
        }
    }

    public function openCreateSubsystemModal(): void
    {
        $this->resetValidation();
        $this->formCode = '';
        $this->formName = '';
        $this->formDescription = '';
        $this->formRoute = '/';
        $this->formOrder = (int) (CoreModule::max('order') ?? 0) + 1;
        $this->formIcon = '🏢';
        $this->formAccentColorHex = '#16a34a';
        $this->formAccentColor = 'emerald';
        $this->formStatus = 'active';
        $this->formStatusMessage = '';
        $this->formWaitTime = '';
        $this->formIsActive = true;
        $this->formRequiresAuth = true;
        $this->selectedModuleId = null;
        $this->showEditModal = false;
        $this->showSubsystemModal = true;
        $this->showCreateModal = true;
    }

    public function openEditSubsystemModal(int $moduleId): void
    {
        $this->resetValidation();
        $module = CoreModule::find($moduleId);

        if ($module) {
            $this->selectedModuleId = $moduleId;
            $this->formCode = (string) $module->code;
            $this->formName = (string) $module->name;
            $this->formDescription = (string) $module->description;
            $this->formRoute = (string) $module->route;
            $this->formOrder = (int) ($module->order ?? 1);
            $this->formIcon = match ($module->code) {
                'central' => '🏢',
                'transportes' => '🚗',
                'itam' => '💻',
                'helpdesk' => '🎧',
                default => ($module->icon_url && mb_strlen($module->icon_url) <= 4 ? $module->icon_url : '🏢'),
            };
            $this->formAccentColor = $module->accent_color->value;
            $this->formAccentColorHex = match ($module->accent_color->value) {
                'amber' => '#ea580c',
                'blue' => '#2563eb',
                'purple' => '#7c3aed',
                default => '#16a34a',
            };
            $this->formStatus = $module->status->value;
            $this->formStatusMessage = (string) ($module->status_message ?? '');
            $this->formWaitTime = (string) ($module->wait_time ?? '');
            $this->formIsActive = (bool) $module->is_active;
            $this->formRequiresAuth = (bool) $module->requires_auth;
            $this->showCreateModal = false;
            $this->showSubsystemModal = true;
            $this->showEditModal = true;
        }
    }

    

    public function saveModule(): void
    {
        $this->validate([
            'formCode' => ['required', 'string', 'max:50'],
            'formName' => ['required', 'string', 'max:100'],
            'formDescription' => ['required', 'string', 'max:500'],
            'formRoute' => ['required', 'string', 'max:100'],
            'formOrder' => ['required', 'integer', 'min:1'],
        ], [
            'formCode.required' => 'El código identificador es obligatorio.',
            'formName.required' => 'El nombre del subsistema es obligatorio.',
            'formDescription.required' => 'La descripción funcional es obligatoria.',
            'formRoute.required' => 'La ruta de acceso es obligatoria.',
            'formOrder.required' => 'El orden de visualización es obligatorio.',
        ]);

        $accentEnum = match ($this->formAccentColorHex) {
            '#ea580c', '#dc2626', '#d97706', 'amber' => ModuleAccentColor::Amber,
            '#2563eb', '#0891b2', 'blue' => ModuleAccentColor::Blue,
            '#7c3aed', 'purple' => ModuleAccentColor::Purple,
            default => ModuleAccentColor::Emerald,
        };

        if ($this->showEditModal && $this->selectedModuleId !== null) {
            $module = CoreModule::find($this->selectedModuleId);
            if ($module) {
                $module->code = strtolower(trim($this->formCode));
                $module->name = trim($this->formName);
                $module->description = trim($this->formDescription);
                $module->route = trim($this->formRoute);
                $module->order = $this->formOrder;
                $module->icon_url = $this->formIcon;
                $module->accent_color = $accentEnum;
                $module->is_active = $this->formIsActive;
                $module->requires_auth = $this->formRequiresAuth;
                $module->save();

                $this->flashMessage = "Subsistema '{$module->name}' actualizado correctamente.";
            }
        } else {
            $module = new CoreModule;
            $module->code = strtolower(trim($this->formCode));
            $module->name = trim($this->formName);
            $module->description = trim($this->formDescription);
            $module->route = trim($this->formRoute);
            $module->order = $this->formOrder;
            $module->icon_url = $this->formIcon;
            $module->accent_color = $accentEnum;
            $module->status = ModuleStatus::Active;
            $module->is_active = $this->formIsActive;
            $module->requires_auth = $this->formRequiresAuth;
            $module->save();

            $this->flashMessage = "Subsistema '{$module->name}' registrado exitosamente.";
        }

        $this->closeModal();
    }

    
    public function render(
        GetAdminDashboardMetricsAction $metricsAction,
        GetOfficesCatalogAction $officesAction,
        GetPeopleCatalogAction $peopleAction,
        GetUsersCatalogAction $usersAction,
        GetRolesCatalogAction $rolesAction,
        GetPermissionsCatalogAction $permissionsAction,
        GetRolePermissionsMatrixAction $matrixAction,
        GetSubsystemsCatalogAction $subsystemsAction
    ): View {
        $metrics = $metricsAction->handle();

        $filteredOffices = $officesAction->execute($this->search, $this->sedeFilter ?? 'TODOS', $this->levelFilter ?? 'TODOS');
        $totalFilteredOffices = count($filteredOffices);
        $totalPagesOffices = max(1, (int) ceil($totalFilteredOffices / $this->perPage));
        if ($this->page > $totalPagesOffices) {
            $this->page = $totalPagesOffices;
        }
        $offsetOffices = ($this->page - 1) * $this->perPage;
        $paginatedOffices = array_slice($filteredOffices, $offsetOffices, $this->perPage);

        $peopleResult = $peopleAction->handle(
            filterType: $this->filterType ?? 'TODOS',
            search: $this->search,
            office: $this->officeFilter ?? '',
            page: $this->page,
            perPage: $this->perPage
        );

        $usersResult = $usersAction->handle(
            search: $this->search,
            roleFilter: $this->roleFilter ?? 'TODOS',
            page: $this->page,
            perPage: $this->perPage
        );

        $rolesResult = $rolesAction->handle(
            search: $this->search,
            scopeFilter: $this->scopeFilter ?? 'TODOS',
            page: $this->page,
            perPage: $this->perPage
        );

        $permsResult = $permissionsAction->handle(
            search: $this->search,
            subsystemFilter: $this->subsystemFilter ?? 'TODOS'
        );

        $matrixData = $matrixAction->handle();
        $matrixRoles = $matrixData['roles'];
        $matrixSubsystems = $permsResult['subsystems'];
        $activeRole = $matrixRoles[$this->selectedRoleCode ?? 'admin_central'] ?? reset($matrixRoles);
        $activePermissions = $this->assignments[$this->selectedRoleCode ?? 'admin_central'] ?? ($activeRole['permissions'] ?? []);
        $grantedCount = count($activePermissions);
        $totalPerms = $permsResult['total'] ?? 19;
        $coveragePercentage = $totalPerms > 0 ? (int) round(($grantedCount / $totalPerms) * 100) : 0;
        $allCodes = collect($matrixSubsystems)->flatMap(fn ($s) => collect($s['permissions'])->pluck('code'))->values()->all();

        $subsysMetrics = $subsystemsAction->getMetrics();
        $subsysModules = $subsystemsAction->execute($this->search, $this->filter ?? 'todos');

        $officesList = Office::orderBy('name')->get();
        $personnelList = Person::where('type', 'PERSONAL')->where('is_active', true)->orderBy('first_name')->get();

        $tabMetrics = match ($this->activeTab) {
            'subsystems' => $subsysMetrics,
            'offices' => $officesAction->getMetrics(),
            default => $metrics,
        };

        $tabCounts = match ($this->activeTab) {
            'roles' => $rolesResult['counts'],
            'permissions' => $permsResult['counts'],
            default => $peopleResult['counts'],
        };

        $tabSubsystems = match ($this->activeTab) {
            'permissions' => $permsResult['subsystems'],
            'matrix' => $matrixSubsystems,
            default => $metrics['subsystems'],
        };

        $tabOffices = match ($this->activeTab) {
            'offices' => $paginatedOffices,
            default => $officesList,
        };

        $totalRoles = $rolesResult['total'];

        return view('livewire.admin.dashboard', [
            'peopleCounts' => $metrics['peopleCounts'],
            'usersCount' => $metrics['usersCount'],
            'rolesCount' => $metrics['rolesCount'],
            'permissionsCount' => $metrics['permissionsCount'],
            'subsystems' => $tabSubsystems,
            'totalSubsystems' => $metrics['totalSubsystems'],
            'activeSubsystemsCount' => $metrics['activeSubsystemsCount'],
            'offices' => $tabOffices,
            'personnelList' => $personnelList,
            'activeTab' => $this->activeTab,

            'metrics' => $tabMetrics,
            'counts' => $tabCounts,

            'people' => $peopleResult['items'],
            'total' => $this->activeTab === 'roles' ? $totalRoles : ($this->activeTab === 'permissions' ? $permsResult['total'] : $peopleResult['total']),
            'currentPage' => $this->page,
            'totalPages' => $this->activeTab === 'offices' ? $totalPagesOffices : ($this->activeTab === 'roles' ? $rolesResult['totalPages'] : ($this->activeTab === 'users' ? $usersResult['totalPages'] : $peopleResult['totalPages'])),
            'from' => $this->activeTab === 'roles' ? $rolesResult['from'] : ($this->activeTab === 'users' ? $usersResult['from'] : $peopleResult['from']),
            'to' => $this->activeTab === 'roles' ? $rolesResult['to'] : ($this->activeTab === 'users' ? $usersResult['to'] : $peopleResult['to']),

            'users' => $usersResult['items'],
            'presets' => GetUsersCatalogAction::ROLE_PRESETS,

            'roles' => $rolesResult['items'],
            'totalRoles' => $totalRoles,

            'permissions' => $permsResult,

            'matrixRoles' => $matrixRoles,
            'totalPermissions' => $permsResult['total'],
            'grantedCount' => $grantedCount,
            'coveragePercentage' => $coveragePercentage,
            'allCodes' => $allCodes,
            'matrixSubsystems' => $matrixSubsystems,
            'activeRole' => $activeRole,
            'activePermissions' => $activePermissions,

            'officeMetrics' => $officesAction->getMetrics(),
            'sedes' => $officesAction->getSedes(),
            'treeOffices' => $officesAction->getTree(),
            'allOffices' => $filteredOffices,
            'allOfficesCatalog' => $officesAction->execute(),
            'totalFiltered' => $totalFilteredOffices,
            'sedesModels' => Sede::withCount('offices')->orderBy('name')->get(),
            'availableColors' => Sede::availableColors(),
            'currentSedeBadgePreview' => Sede::COLOR_PRESETS[$this->sedeFormColor ?? 'emerald']['classes'] ?? Sede::COLOR_PRESETS['emerald']['classes'],

            'moduleMetrics' => $subsysMetrics,
            'modules' => $subsysModules,
            'totalCount' => $subsysModules->count(),
        ])->layout('components.layouts.subsystem', [
            'title' => 'Panel de Administración - SIGM Castilla',
            'moduleName' => 'Panel de Administración',
            'subsystem' => 'admin',
            'activeTab' => $this->activeTab,
            'counts' => [
                'people' => $metrics['peopleCounts']['total'] ?? 46,
                'users' => $metrics['usersCount'] ?? 49,
                'roles' => $metrics['rolesCount'] ?? 4,
                'permissions' => $metrics['permissionsCount'] ?? 19,
            ],
        ]);
    }
}