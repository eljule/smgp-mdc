<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\Admin\GetPermissionsCatalogAction;
use App\Actions\Admin\GetRolePermissionsMatrixAction;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.subsystem', [
    'title' => 'Matriz de Asignación de Permisos por Rol - SIGM Castilla',
    'moduleName' => 'Panel de Administración',
    'subsystem' => 'admin',
    'activeTab' => 'assignment',
    'counts' => [
        'people' => 46,
        'users' => 49,
        'roles' => 4,
        'permissions' => 19,
    ],
])]
#[Title('Matriz de Asignación de Permisos por Rol - SIGM Castilla')]
class PermissionMatrix extends Component
{
    #[Url(as: 'rol')]
    public string $selectedRoleCode = 'admin_central';

    /**
     * @var array<string, array<int, string>>
     */
    public array $assignments = [];

    public string $flashMessage = '';

    public function hydrate(): void
    {
        $this->flashMessage = '';
    }

    public bool $hasChanges = false;

    /**
     * Initialize assignments with catalog defaults.
     */
    public function mount(GetRolePermissionsMatrixAction $matrixAction): void
    {
        $this->assignments = $matrixAction->handle()['assignments'];
    }

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
        $this->hasChanges = false;
        $this->flashMessage = 'Permisos del rol actualizados correctamente.';
    }

    /**
     * Reset unsaved changes to defaults.
     */
    public function resetMatrix(GetRolePermissionsMatrixAction $matrixAction): void
    {
        $this->assignments = $matrixAction->handle()['assignments'];
        $this->hasChanges = false;
        $this->flashMessage = 'Cambios descartados y permisos restablecidos.';
    }

    /**
     * Render the matrix view.
     */
    public function render(
        GetRolePermissionsMatrixAction $matrixAction,
        GetPermissionsCatalogAction $catalogAction
    ): View {
        $matrixData = $matrixAction->handle();
        $roles = $matrixData['roles'];
        $catalog = $catalogAction->handle();
        $subsystems = $catalog['subsystems'];

        $activeRole = $roles[$this->selectedRoleCode] ?? reset($roles);
        $activePermissions = $this->assignments[$this->selectedRoleCode] ?? [];

        // Collect all available codes
        $allCodes = [];
        foreach ($subsystems as $sub) {
            foreach ($sub['permissions'] as $p) {
                $allCodes[] = $p['code'];
            }
        }

        $grantedCount = count($activePermissions);
        $totalPermissions = count($allCodes);
        $coveragePercentage = $totalPermissions > 0 ? (int) round(($grantedCount / $totalPermissions) * 100) : 0;

        return view('livewire.admin.permission-matrix', [
            'roles' => $roles,
            'activeRole' => $activeRole,
            'subsystems' => $subsystems,
            'activePermissions' => $activePermissions,
            'grantedCount' => $grantedCount,
            'totalPermissions' => $totalPermissions,
            'coveragePercentage' => $coveragePercentage,
            'allCodes' => $allCodes,
        ]);
    }
}
