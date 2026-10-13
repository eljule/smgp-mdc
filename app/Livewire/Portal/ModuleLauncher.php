<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Actions\Core\GetActiveModulesAction;
use App\Livewire\Concerns\WithSearch;
use App\Models\CoreModule;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Panel de Subsistemas - SIGM Castilla')]
class ModuleLauncher extends Component
{
    use WithSearch;

    public ?string $activeUser = null;

    public function mount(?string $user = null): void
    {
        if ($user !== null) {
            $this->activeUser = $user;
        }
    }

    /**
     * Render the module launcher portal view.
     * Delegates data retrieval exclusively to GetActiveModulesAction.
     */
    public function render(GetActiveModulesAction $action): View
    {
        $authUser = auth()->user();
        $targetUser = $this->activeUser ?: ($authUser?->username ?? 'root');

        $modules = $action->handle($this->search, $targetUser);
        $totalActiveCount = CoreModule::query()->active()->count();

        $normalized = strtolower(trim($targetUser));

        $userInfo = match ($normalized) {
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
                'initials' => $authUser?->initials ?? 'RO',
                'name' => $authUser?->username ?? $targetUser,
                'title' => $authUser ? ($authUser->display_title ?? mb_strtoupper($authUser->full_name)) : 'ADMINISTRADOR DEL SISTEMA SIGM',
                'role' => $authUser?->display_role ?? 'Administrador Central',
                'description' => 'Cuenta con privilegios totales de administración. Todos los subsistemas distritales se encuentran habilitados.',
                'assignedCount' => 4,
            ],
        };

        return view('livewire.portal.module-launcher', [
            'modules' => $modules,
            'totalActiveCount' => $totalActiveCount,
            'userInfo' => $userInfo,
        ])->layout('components.layouts.app', [
            'userInfo' => $userInfo,
        ]);
    }
}
