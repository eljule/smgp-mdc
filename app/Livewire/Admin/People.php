<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\Admin\GetPeopleCatalogAction;
use App\Models\Itam\Asset;
use App\Models\Itam\AssetMovement;
use App\Models\Office;
use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.subsystem', [
    'title' => 'Tabla Maestra de Personas - SIGM Castilla',
    'moduleName' => 'Panel de Administración',
    'subsystem' => 'admin',
    'activeTab' => 'people',
    'counts' => [
        'people' => 46,
        'users' => 49,
        'roles' => 4,
        'permissions' => 19,
    ],
])]
#[Title('Tabla Maestra de Personas - SIGM Castilla')]
class People extends Component
{
    #[Url(as: 'tipo')]
    public string $filterType = 'TODOS';

    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'pagina')]
    public int $page = 1;

    public int $perPage = 10;

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

    public ?string $alertMessage = null;

    public function hydrate(): void
    {
        $this->alertMessage = null;
    }

    public string $alertType = 'success';

    public function showAlert(string $message, string $type = 'success'): void
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
    }

    public function setFilter(string $type): void
    {
        $this->filterType = in_array($type, ['TODOS', 'PERSONAL', 'ADMINISTRADO'], true) ? $type : 'TODOS';
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

    // ==========================================
    // MODAL DE REGISTRO / EDICIÓN
    // ==========================================
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->editingPersonId = null;
        $defaultOffice = Office::first();
        if ($defaultOffice) {
            $this->formOffice = "[{$defaultOffice->acronym}] {$defaultOffice->name}";
        }
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
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
        $this->resetForm();
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

    public function resetForm(): void
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

    public function closeModal(): void
    {
        $this->modalError = null;
        $this->showFormModal = false;
        $this->showCeaseModal = false;
        $this->showCeaseDetailModal = false;
    }

    public function render(GetPeopleCatalogAction $action): View
    {
        $result = $action->handle(
            filterType: $this->filterType,
            search: $this->search,
            page: $this->page,
            perPage: $this->perPage
        );

        $offices = Office::orderBy('name')->get();

        return view('livewire.admin.people', [
            'people' => $result['items'],
            'total' => $result['total'],
            'currentPage' => $result['currentPage'],
            'perPage' => $result['perPage'],
            'totalPages' => $result['totalPages'],
            'from' => $result['from'],
            'to' => $result['to'],
            'counts' => $result['counts'],
            'offices' => $offices,
        ]);
    }
}
