<?php

declare(strict_types=1);

namespace App\Livewire\Itam;

use App\Actions\Itam\GetMovementsCatalogAction;
use App\Models\Itam\Asset;
use App\Models\Itam\AssetMovement;
use App\Models\Office;
use App\Models\Person;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Movimientos y Actas de Custodia - SIGM Castilla',
    'moduleName' => 'Inventario y Gestión TI',
    'subsystem' => 'itam',
    'activeTab' => 'movements',
])]
#[Title('Movimientos y Actas de Custodia - SIGM Castilla')]
class Movements extends Component
{
    use WithPagination;

    #[Url(as: 'buscar')]
    public string $search = '';

    public int $perPage = 10;

    public bool $showCreateModal = false;

    public ?string $flashMessage = null;

    public string $flashType = 'success';

    public function hydrate(): void
    {
        $this->flashMessage = null;
    }

    public ?int $formAssetId = null;

    public string $formAssetSearch = '';

    public ?Asset $selectedAsset = null;

    public string $formMovementType = 'TRASLADO';

    public string $formToOffice = '';

    public ?int $formToPersonId = null;

    public string $formNewCustodianName = '';

    public bool $manualCustodian = false;

    public string $formTechnicianName = 'Técnico de Soporte TI';

    public string $formReason = '';

    public string $formNotes = '';

    public bool $formIncludeChildren = true;

    public array $municipalOffices = [
        '[OCC] OFICINA DE CONTABILIDAD Y COSTOS - PALACIO MUNICIPAL (SEDE PRINCIPAL)',
        '[GAT] GERENCIA DE ADMINISTRACIÓN TRIBUTARIA - PALACIO MUNICIPAL',
        '[GM] GERENCIA MUNICIPAL - SEDE PRINCIPAL',
        '[ALC] ALCALDÍA - PALACIO MUNICIPAL',
        '[SGL] SUBGERENCIA DE LOGÍSTICA Y PATRIMONIO - MAESTRANZA',
        '[RRHH] SUBGERENCIA DE RECURSOS HUMANOS - PALACIO MUNICIPAL',
        '[GSC] GERENCIA DE SEGURIDAD CIUDADANA - BASE SERENAZGO',
        '[GDU] GERENCIA DE DESARROLLO URBANO - PALACIO MUNICIPAL',
        '[TI] OFICINA DE TECNOLOGÍAS DE LA INFORMACIÓN - ALMACÉN CENTRAL',
        'Oficina de Desarrollo Tecnológico',
        'Alcaldía',
        'Gerencia Municipal',
        'Secretaría General y Mesa de Partes',
        'Gerencia de Administración y Finanzas',
        'Subgerencia de Recursos Humanos',
        'Subgerencia de Logística y Patrimonio',
        'Gerencia de Rentas y Administración Tributaria',
        'Subgerencia de Fiscalización y Control',
        'Subgerencia de Transportes y Tránsito',
        'Gerencia de Desarrollo Urbano e Infraestructura',
        'Gerencia de Seguridad Ciudadana y Serenazgo',
        'Subgerencia de Gestión Ambiental y Limpieza',
    ];

    public function toggleManualCustodian(): void
    {
        $this->manualCustodian = ! $this->manualCustodian;
    }

    public function openCreateModal(?int $assetId = null): void
    {
        $this->formAssetId = $assetId;
        $this->formAssetSearch = '';
        $this->selectedAsset = $assetId ? Asset::with(['brand', 'model', 'assignedPerson', 'childAssets'])->find($assetId) : null;
        $this->formMovementType = 'TRASLADO';
        $this->formToOffice = '';
        $this->formToPersonId = null;
        $this->formNewCustodianName = '';
        $this->manualCustodian = false;
        $this->formTechnicianName = 'Técnico de Soporte TI';
        $this->formReason = '';
        $this->formNotes = '';
        $this->formIncludeChildren = true;
        $this->resetValidation();
        $this->showCreateModal = true;
    }

    public function updatedFormAssetId(): void
    {
        $this->selectedAsset = $this->formAssetId ? Asset::with(['brand', 'model', 'assignedPerson', 'childAssets'])->find($this->formAssetId) : null;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->formAssetId = null;
        $this->selectedAsset = null;
        $this->resetValidation();
    }

    public function getAvailableAssetsProperty()
    {
        $query = Asset::with(['brand', 'model', 'assignedPerson'])
            ->where('is_active', true);

        if (! empty(trim($this->formAssetSearch))) {
            $s = '%'.trim($this->formAssetSearch).'%';
            $query->where(function ($q) use ($s) {
                $q->where('computer_code', 'ILIKE', $s)
                    ->orWhere('patrimonial_code', 'ILIKE', $s)
                    ->orWhere('serial_number', 'ILIKE', $s)
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'ILIKE', $s))
                    ->orWhereHas('model', fn ($m) => $m->where('name', 'ILIKE', $s))
                    ->orWhereHas('assignedPerson', fn ($p) => $p->whereRaw("CONCAT(first_name, ' ', paternal_surname, ' ', maternal_surname) ILIKE ?", [$s]));
            });
        }

        $assets = $query->orderBy('computer_code')->take(50)->get();

        if ($this->formAssetId && ! $assets->contains('id', $this->formAssetId)) {
            $selected = Asset::with(['brand', 'model', 'assignedPerson'])->find($this->formAssetId);
            if ($selected) {
                $assets->prepend($selected);
            }
        }

        return $assets;
    }

    public function saveMovement(): void
    {
        $this->validate([
            'formAssetId' => 'required|exists:itam_assets,id',
            'formToOffice' => 'required|string|max:150',
            'formReason' => 'required|string|max:500',
        ], [
            'formAssetId.required' => 'Debe seleccionar el activo informático a trasladar o reasignar.',
            'formToOffice.required' => 'La oficina / dependencia de destino es obligatoria.',
            'formReason.required' => 'El motivo de la transferencia / justificación es obligatorio.',
        ]);

        $asset = Asset::with(['brand', 'model', 'childAssets', 'assignedPerson'])->find($this->formAssetId);
        if (! $asset) {
            return;
        }

        $count = AssetMovement::count() + 1;
        $actaNumber = 'MDC-ACTA-MOV-'.date('Y').'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        while (AssetMovement::where('acta_number', $actaNumber)->exists()) {
            $count++;
            $actaNumber = 'MDC-ACTA-MOV-'.date('Y').'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        }

        // Buscar custodio receptor
        $toPersonId = $this->formToPersonId;
        if ($this->manualCustodian && ! empty(trim($this->formNewCustodianName))) {
            $cleanName = trim($this->formNewCustodianName);
            $person = Person::whereRaw("CONCAT(first_name, ' ', paternal_surname, ' ', maternal_surname) ILIKE ?", ["%{$cleanName}%"])
                ->orWhere('document_number', $cleanName)
                ->first();
            $toPersonId = $person?->id;
        }

        AssetMovement::create([
            'acta_number' => $actaNumber,
            'asset_id' => $asset->id,
            'from_office' => $asset->office ?: 'Almacén Central TI',
            'to_office' => $this->formToOffice,
            'from_person_id' => $asset->assigned_person_id,
            'to_person_id' => $toPersonId,
            'movement_date' => date('Y-m-d'),
            'movement_type' => $this->formMovementType,
            'reason' => $this->formReason,
            'technician_name' => $this->formTechnicianName ?: 'Técnico de Soporte TI',
            'notes' => $this->formNotes,
            'include_children_in_transfer' => $this->formIncludeChildren,
        ]);

        // Reasignar activo
        $asset->office = $this->formToOffice;
        if ($toPersonId) {
            $asset->assigned_person_id = $toPersonId;
        } elseif ($this->formMovementType === 'RETORNO') {
            $asset->assigned_person_id = null;
        } elseif (! $this->manualCustodian && ! $this->formToPersonId) {
            $asset->assigned_person_id = null;
        }

        if ($this->formMovementType === 'BAJA') {
            $asset->status = 'BAJA';
        } elseif ($asset->status === 'BAJA') {
            $asset->status = 'OPERATIVO';
        }
        $asset->save();

        if ($this->formIncludeChildren && $asset->childAssets()->exists()) {
            $asset->childAssets()->update([
                'office' => $this->formToOffice,
                'assigned_person_id' => $toPersonId,
            ]);
        }

        session()->flash('success', "Movimiento registrado con éxito. Acta oficial {$actaNumber} generada.");
        $this->flashMessage = "Movimiento registrado con éxito. Acta oficial {$actaNumber} generada.";
        $this->showCreateModal = false;
    }

    public function render(GetMovementsCatalogAction $action): View
    {
        $movements = $action->handle(search: $this->search, perPage: $this->perPage);
        $offices = Office::where('is_active', true)->orderBy('name')->get();
        $people = Person::where('is_active', true)->orderBy('first_name')->take(100)->get();

        return view('livewire.itam.movements', [
            'movements' => $movements,
            'offices' => $offices,
            'people' => $people,
            'municipalOffices' => $this->municipalOffices,
        ]);
    }
}
