<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Actions\Core\GetActiveModulesAction;
use App\Livewire\Concerns\WithSearch;
use App\Models\CoreModule;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Panel de Subsistemas - SIGM Castilla')]
class ModuleLauncher extends Component
{
    use WithSearch;

    #[Url(as: 'user', except: '')]
    public string $activeUser = 'ROOT';

    /**
     * Render the module launcher portal view.
     * Delegates data retrieval exclusively to GetActiveModulesAction.
     */
    public function render(GetActiveModulesAction $action): View
    {
        $modules = $action->handle($this->search, $this->activeUser);
        $totalActiveCount = CoreModule::query()->active()->count();

        $userInfo = match (strtolower(trim($this->activeUser))) {
            'jperez' => [
                'initials' => 'JP',
                'name' => 'jperez',
                'title' => 'PERSONAL TÉCNICO TI',
                'role' => 'Administrador ITAM & Soporte',
                'description' => 'Acceso concedido a 3 subsistemas conforme a las políticas de seguridad de la ODT.',
                'assignedCount' => 3,
            ],
            'mgomez' => [
                'initials' => 'MG',
                'name' => 'mgomez',
                'title' => 'COLABORADOR MUNICIPAL',
                'role' => 'Usuario Operativo',
                'description' => 'Acceso concedido a 2 subsistemas conforme a las políticas de seguridad de la ODT.',
                'assignedCount' => 2,
            ],
            default => [
                'initials' => 'RO',
                'name' => 'root',
                'title' => 'ADMINISTRADOR DEL SISTEMA SIGM',
                'role' => 'Administrador Central',
                'description' => 'Cuenta con privilegios totales de administración. Todos los subsistemas distritales se encuentran habilitados.',
                'assignedCount' => 4,
            ],
        };

        return view('livewire.portal.module-launcher', [
            'modules' => $modules,
            'totalActiveCount' => $totalActiveCount,
            'userInfo' => $userInfo,
        ]);
    }
}
