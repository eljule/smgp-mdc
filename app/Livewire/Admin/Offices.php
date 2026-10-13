<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\Admin\GetOfficesCatalogAction;
use App\Models\Office;
use App\Models\Sede;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.subsystem', [
    'title' => 'Estructura Orgánica y Dependencias - SIGM Castilla',
    'moduleName' => 'Panel de Administración',
    'subsystem' => 'admin',
    'activeTab' => 'offices',
    'counts' => [
        'people' => 46,
        'users' => 49,
        'roles' => 4,
        'permissions' => 19,
    ],
])]
#[Title('Estructura Orgánica y Dependencias - SIGM Castilla')]
class Offices extends Component
{
    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'sede')]
    public string $sedeFilter = 'TODAS';

    #[Url(as: 'nivel')]
    public string $levelFilter = 'TODOS';

    #[Url(as: 'vista')]
    public string $viewMode = 'table';

    #[Url(as: 'pagina')]
    public int $page = 1;

    public int $perPage = 10;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

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

    public string $flashMessage = '';

    // ==========================================
    // PROPIEDADES PARA MODAL DE CÓDIGO QR DE OFICINA
    // ==========================================
    public bool $showQrModal = false;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $selectedOfficeForQr = null;

    public function hydrate(): void
    {
        $this->flashMessage = '';
    }

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

    public function updatedSearch(): void
    {
        $this->page = 1;
    }

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

    public function setPage(int $page): void
    {
        $this->page = max(1, $page);
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
            $this->formParentName = $parent->name;
            $this->formLevel = min(4, ((int) $parent->level) + 1);
        } else {
            $this->formParentName = '';
            $this->formLevel = 1;
        }
    }

    public function openCreateModal(): void
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
        $this->showCreateModal = true;
    }

    public function openEditModal(int $id, GetOfficesCatalogAction $action): void
    {
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
            $this->formParentName = (string) ($office->parent?->name ?? $office->parent_name ?? '');
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
            $this->showEditModal = true;
        }
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->selectedOfficeId = null;
        $this->selectedOffice = null;
        $this->resetValidation();
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

    public function render(GetOfficesCatalogAction $action): View
    {
        $metrics = $action->getMetrics();
        $sedes = $action->getSedes();
        $filteredOffices = $action->execute($this->search, $this->sedeFilter, $this->levelFilter);

        $totalFiltered = count($filteredOffices);
        $totalPages = max(1, (int) ceil($totalFiltered / $this->perPage));

        if ($this->page > $totalPages) {
            $this->page = $totalPages;
        }

        $offset = ($this->page - 1) * $this->perPage;
        $paginatedOffices = array_slice($filteredOffices, $offset, $this->perPage);

        $sedesModels = Sede::withCount('offices')->orderBy('name')->get();
        $currentSedeBadgePreview = Sede::COLOR_PRESETS[$this->sedeFormColor]['classes'] ?? Sede::COLOR_PRESETS['emerald']['classes'];

        return view('livewire.admin.offices', [
            'metrics' => $metrics,
            'sedes' => $sedes,
            'offices' => $paginatedOffices,
            'treeOffices' => $action->getTree(),
            'allOffices' => $filteredOffices,
            'allOfficesCatalog' => $action->execute(),
            'totalFiltered' => $totalFiltered,
            'totalPages' => $totalPages,
            'currentPage' => $this->page,
            'sedesModels' => $sedesModels,
            'availableColors' => Sede::availableColors(),
            'currentSedeBadgePreview' => $currentSedeBadgePreview,
        ]);
    }
}
