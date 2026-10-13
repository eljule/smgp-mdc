<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\Admin\GetSubsystemsCatalogAction;
use App\Enums\ModuleAccentColor;
use App\Enums\ModuleStatus;
use App\Models\CoreModule;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.subsystem', [
    'title' => 'Catálogo de Subsistemas SIGM - SIGM Castilla',
    'moduleName' => 'Panel de Administración',
    'subsystem' => 'admin',
    'activeTab' => 'subsystems',
    'counts' => [
        'people' => 46,
        'users' => 49,
        'roles' => 4,
        'permissions' => 19,
    ],
])]
#[Title('Catálogo de Subsistemas SIGM - SIGM Castilla')]
class Subsystems extends Component
{
    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'estado')]
    public string $filter = 'todos';

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $selectedModuleId = null;

    // Form fields
    public string $formCode = '';

    public string $formName = '';

    public string $formDescription = '';

    public string $formRoute = '/';

    public int $formOrder = 5;

    public string $formIcon = '🏢';

    public string $formAccentColorHex = '#16a34a';

    public string $formAccentColor = 'emerald';

    public string $formStatus = 'active';

    public string $formStatusMessage = '';

    public string $formWaitTime = '';

    public bool $formIsActive = true;

    public bool $formRequiresAuth = true;

    public string $flashMessage = '';

    public function hydrate(): void
    {
        $this->flashMessage = '';
    }

    public function updatedSearch(): void
    {
        // Reactivity handled automatically
    }

    public function setFilter(string $filter): void
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

    public function openCreateModal(): void
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
        $this->showCreateModal = true;
    }

    public function openEditModal(int $moduleId): void
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
            $this->showEditModal = true;
        }
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->selectedModuleId = null;
        $this->resetValidation();
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

    public function render(GetSubsystemsCatalogAction $action): View
    {
        $metrics = $action->getMetrics();
        $modules = $action->execute($this->search, $this->filter);

        return view('livewire.admin.subsystems', [
            'metrics' => $metrics,
            'modules' => $modules,
            'totalCount' => $modules->count(),
        ]);
    }
}
