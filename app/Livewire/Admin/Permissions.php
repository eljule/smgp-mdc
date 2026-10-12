<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\Admin\GetPermissionsCatalogAction;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.subsystem', [
    'title' => 'Catálogo de Permisos Granulares - SIGM Castilla',
    'moduleName' => 'Panel de Administración',
    'subsystem' => 'admin',
    'activeTab' => 'permissions',
    'counts' => [
        'people' => 46,
        'users' => 49,
        'roles' => 4,
        'permissions' => 19,
    ],
])]
#[Title('Catálogo de Permisos Granulares - SIGM Castilla')]
class Permissions extends Component
{
    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'subsistema')]
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
    public function clearSearch(): void
    {
        $this->search = '';
    }

    /**
     * Render the permissions catalog view.
     */
    public function render(GetPermissionsCatalogAction $action): View
    {
        $result = $action->handle(
            search: $this->search,
            subsystemFilter: $this->subsystemFilter
        );

        return view('livewire.admin.permissions', [
            'subsystems' => $result['subsystems'],
            'total' => $result['total'],
            'counts' => $result['counts'],
        ]);
    }
}
