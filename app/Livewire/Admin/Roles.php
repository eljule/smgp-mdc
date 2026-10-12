<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\Admin\GetRolesCatalogAction;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.subsystem', [
    'title' => 'Mantenimiento de Roles Institucionales - SIGM Castilla',
    'moduleName' => 'Panel de Administración',
    'subsystem' => 'admin',
    'activeTab' => 'roles',
    'counts' => [
        'people' => 46,
        'users' => 49,
        'roles' => 4,
        'permissions' => 19,
    ],
])]
#[Title('Mantenimiento de Roles Institucionales - SIGM Castilla')]
class Roles extends Component
{
    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'ambito')]
    public string $scopeFilter = 'TODOS';

    #[Url(as: 'pagina')]
    public int $page = 1;

    public int $perPage = 10;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $isEditing = false;

    public bool $autoSlug = true;

    public ?int $selectedRoleId = null;

    public ?array $selectedRole = null;

    // Campos formulario de Rol
    public string $formRoleName = '';

    public string $formRoleCode = '';

    public string $formRoleScope = 'global';

    public string $formRoleDescription = '';

    public string $flashMessage = '';

    public function setScopeFilter(string $scope): void
    {
        $this->scopeFilter = $scope;
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

    public function openCreateModal(): void
    {
        $this->isEditing = false;
        $this->autoSlug = true;
        $this->formRoleName = '';
        $this->formRoleCode = '';
        $this->formRoleScope = 'global';
        $this->formRoleDescription = '';
        $this->showCreateModal = true;
    }

    public function goToMatrix(string $roleCode): void
    {
        $this->redirect(route('admin.matrix', ['rol' => $roleCode]), navigate: true);
    }

    public function openEditModal(int $roleId, GetRolesCatalogAction $action): void
    {
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
            $this->showCreateModal = true;
            $this->showEditModal = true;
        }
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->selectedRoleId = null;
        $this->selectedRole = null;
        $this->isEditing = false;
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

    public function deleteRole(int $roleId, GetRolesCatalogAction $action): void
    {
        $role = $action->find($roleId);

        if ($role && ! $role['isSystem']) {
            $this->flashMessage = "El rol '{$role['name']}' ha sido eliminado con éxito.";
        } else {
            $this->flashMessage = 'No se puede eliminar un rol base protegido del sistema.';
        }
    }

    public function render(GetRolesCatalogAction $action): View
    {
        $result = $action->handle(
            search: $this->search,
            scopeFilter: $this->scopeFilter,
            page: $this->page,
            perPage: $this->perPage
        );

        return view('livewire.admin.roles', [
            'roles' => $result['items'],
            'total' => $result['total'],
            'currentPage' => $result['currentPage'],
            'perPage' => $result['perPage'],
            'totalPages' => $result['totalPages'],
            'from' => $result['from'],
            'to' => $result['to'],
            'counts' => $result['counts'],
        ]);
    }
}
