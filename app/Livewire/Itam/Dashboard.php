<?php

declare(strict_types=1);

namespace App\Livewire\Itam;

use App\Actions\Itam\GetItamDashboardMetricsAction;
use App\Models\Itam\Asset;
use App\Models\Itam\AssetBrand;
use App\Models\Itam\AssetCategory;
use App\Models\Itam\AssetLoan;
use App\Models\Itam\AssetLoanItem;
use App\Models\Itam\AssetModel;
use App\Models\Itam\AssetMovement;
use App\Models\Itam\AuditVerification;
use App\Models\Itam\InventoryAudit;
use App\Models\Itam\MaintenanceOrder;
use App\Models\Itam\Software;
use App\Models\Itam\Supply;
use App\Models\Office;
use App\Models\Person;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Inventario y Gestión Activos TI - SIGM Castilla',
    'moduleName' => 'Inventario y Gestión TI',
    'subsystem' => 'itam',
    'activeTab' => 'dashboard',
])]
#[Title('Inventario y Gestión Activos TI - SIGM Castilla')]
class Dashboard extends Component
{
    use WithPagination;

    #[Url(as: 'tab')]
    public string $activeTab = 'inventory';

    // Filtros de inventario
    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'categoria')]
    public string $selectedCategory = '';

    #[Url(as: 'estado')]
    public string $selectedStatus = '';

    #[Url(as: 'marca')]
    public string $selectedBrand = '';

    public int $perPage = 10;

    // Toast Alert
    public ?string $alertMessage = null;

    public function hydrate(): void
    {
        $this->alertMessage = null;
    }

    public string $alertType = 'success';

    // Búsqueda QR
    public string $quickLookupCode = '';

    public ?Asset $lookupResult = null;

    public ?string $lookupError = null;

    // Modales de Activos
    public bool $isAssetModalOpen = false;

    public ?int $editingAssetId = null;

    public string $formComputerCode = '';

    public string $formPatrimonialCode = '';

    public string $formSerialNumber = '';

    public ?int $formCategoryId = null;

    public ?int $formBrandId = null;

    public ?int $formModelId = null;

    public ?int $formOfficeId = null;

    public string $formOffice = '';

    public ?int $formAssignedPersonId = null;

    public string $formAssignedPersonName = '';

    protected bool $isSyncingOfficeAndPerson = false;

    public string $formColor = '';

    public string $formAcquisitionDate = '';

    public string $formStatus = 'OPERATIVO';

    public string $formPhysicalCondition = 'BUENO';

    public string $formSupplier = '';

    public string $formWarrantyEndDate = '';

    public bool $formIsLoanable = false;

    public string $formNotes = '';

    public array $formSpecifications = [];

    public array $formDynamicAttributes = [];

    // Quick Add Brand / Model
    public bool $showAddBrand = false;

    public string $newBrandName = '';

    public bool $showAddModel = false;

    public string $newModelName = '';

    public array $municipalOffices = [
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

    // Detalle de Activo & QR
    public bool $isDetailModalOpen = false;

    public ?Asset $selectedAssetForDetail = null;

    // Movimiento Modal
    public bool $isMovementModalOpen = false;

    public ?int $movementAssetId = null;

    public string $movementAssetSearch = '';

    public ?Asset $selectedMovementAsset = null;

    public string $movementType = 'TRASLADO';

    public string $movementToOffice = '';

    public ?int $movementTargetPersonId = null;

    public string $movementNewCustodian = '';

    public bool $manualCustodian = false;

    public string $movementTechnician = 'Técnico de Soporte TI';

    public string $movementReason = '';

    public string $movementNotes = '';

    public bool $movementIncludeChildren = true;

    // Mantenimiento Modal
    public bool $isMaintenanceModalOpen = false;

    public ?int $maintenanceAssetId = null;

    public ?Asset $selectedMaintenanceAsset = null;

    public string $maintenanceType = 'PREVENTIVO';

    public string $maintenancePriority = 'MEDIA';

    public string $maintenanceScheduledDate = '';

    public string $maintenanceTechnician = 'Técnico de Soporte TI';

    public string $maintenanceFailureReported = '';

    public string $maintenanceNotes = '';

    // Insumo Modal
    public bool $isSupplyModalOpen = false;

    public ?int $editingSupplyId = null;

    public string $formSupplyCode = '';

    public string $formSupplyName = '';

    public string $formSupplyCategory = 'TONER_TINTA';

    public int $formSupplyStock = 10;

    public int $formSupplyMinStock = 2;

    public string $formSupplyUnit = 'UNIDAD';

    public string $formSupplyLocation = 'Almacén ODT - Estante 1';

    public string $formSupplyNotes = '';

    public bool $supplyAdjustMode = false;

    public int $supplyDelta = 0;

    public string $supplyAdjustReason = '';

    // Préstamo Modal
    public bool $isLoanModalOpen = false;

    public ?int $editingLoanId = null;

    public ?string $editingLoanNumber = null;

    public array $loanSelectedAssetIds = [];

    public string $loanDepartment = '';

    public string $loanBorrowerName = '';

    public string $loanStartDate = '';

    public string $loanExpectedReturnDate = '';

    public string $loanReason = '';

    public string $loanNotes = '';

    public string $loanReturnCondition = 'BUENO';

    public string $loanReturnNotes = '';

    public ?int $loanAssetId = null;

    public ?int $loanOfficeId = null;

    // Auditoría Modal
    public bool $isAuditModalOpen = false;

    public bool $isAuditReconciliationModalOpen = false;

    public ?InventoryAudit $selectedAuditForModal = null;

    public string $auditScanCode = '';

    public string $auditFoundOffice = '';

    public string $auditVerificationStatus = 'CONCILIADO';

    public string $auditFilterStatus = 'ALL';

    public int $formAuditYear = 2026;

    public string $formAuditTitle = 'Inventario Físico Anual 2026';

    public string $formAuditNotes = '';

    public string $formAuditPeriod = '2026-I';

    // Software Modal
    public bool $isSoftwareModalOpen = false;

    public ?int $editingSoftwareId = null;

    public string $formSoftwareName = '';

    public string $formSoftwareVersion = '';

    public string $formSoftwareDeveloper = '';

    public string $formSoftwareLicenseType = 'OEM';

    public int $formSoftwareTotalLicenses = 1;

    public string $formSoftwareLicenseKey = '';

    public ?string $formSoftwareExpirationDate = null;

    public string $formSoftwareNotes = '';

    // Marca y Modelo Modal
    public bool $isBrandModelModalOpen = false;

    public string $catalogType = 'brand'; // 'brand' | 'model'

    public ?int $editingBrandId = null;

    public ?int $editingModelId = null;

    public string $formBrandName = '';

    public string $formBrandDescription = '';

    public bool $formBrandIsActive = true;

    public ?int $formModelBrandId = null;

    public ?int $formModelCategoryId = null;

    public string $formModelName = '';

    public string $formModelDescription = '';

    public bool $formModelIsActive = true;

    // Filtros de Catálogos (Tab 9)
    public string $searchBrand = '';

    public string $searchModel = '';

    public string $filterModelBrandId = '';

    // Categoría Modal
    public bool $isCategoryModalOpen = false;

    public ?int $editingCategoryId = null;

    public string $formCatName = '';

    public string $formCatCode = '';

    public string $formCatDescription = '';

    public string $formCatIcon = '💻';

    public string $formCatColor = '#2563eb';

    public array $presetCatIcons = ['💻', '🖥️', '🖨️', '🖱️', '📱', '📡', '🖲️', '🔌', '🔋', '🎧', '📹', '📦'];

    public array $presetCatColors = ['#2563eb', '#0891b2', '#16a34a', '#d97706', '#9333ea', '#e11d48', '#4f46e5', '#475569'];

    // Acta Oficial PDF Modal
    public bool $isActaModalOpen = false;

    public string $actaType = 'ASIGNACION';

    public ?Asset $actaAsset = null;

    public ?AssetMovement $actaMovement = null;

    public ?MaintenanceOrder $actaMaintenance = null;

    public ?AssetLoan $actaLoan = null;

    // Periféricos e Hijos Modal
    public bool $isChildModalOpen = false;

    public ?Asset $parentForChildModal = null;

    public ?int $selectedChildAssetId = null;

    // Esquemas Dinámicos de Categoría Modal
    public bool $isCategorySchemaModalOpen = false;

    public ?AssetCategory $categoryForSchema = null;

    public array $schemaFields = [];

    public string $schemaTab = 'editor'; // 'editor' | 'preview'

    public ?string $schemaErrorMsg = null;

    public array $schemaPreviewValues = [];

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function showAlert(string $message, string $type = 'success'): void
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
    }

    public function handleQuickLookup(): void
    {
        if (empty(trim($this->quickLookupCode))) {
            return;
        }

        $code = trim($this->quickLookupCode);
        $asset = Asset::with(['category', 'brand', 'model', 'assignedPerson'])
            ->where('computer_code', 'ILIKE', "%{$code}%")
            ->orWhere('patrimonial_code', 'ILIKE', "%{$code}%")
            ->orWhere('serial_number', 'ILIKE', "%{$code}%")
            ->first();

        if ($asset) {
            $this->lookupResult = $asset;
            $this->lookupError = null;
            $this->showAlert("Activo encontrado: {$asset->computer_code}");
        } else {
            $this->lookupResult = null;
            $this->lookupError = "No se encontró ningún activo con el código \"{$code}\".";
            $this->showAlert('Código no encontrado', 'error');
        }
    }

    // ==========================================
    // ACTIVOS CRUD
    // ==========================================
    public function updatedFormCategoryId(?int $val): void
    {
        $cat = AssetCategory::find($val);
        $this->initializeSpecifications($cat);
    }

    public function updatedFormBrandId(?int $val): void
    {
        $this->formModelId = null;
        $this->showAddModel = false;
    }

    public function quickAddBrand(): void
    {
        $name = trim($this->newBrandName);
        if ($name !== '') {
            $brand = AssetBrand::firstOrCreate(['name' => $name]);
            $this->formBrandId = $brand->id;
            $this->newBrandName = '';
            $this->showAddBrand = false;
            $this->formModelId = null;
        }
    }

    public function quickAddModel(): void
    {
        $name = trim($this->newModelName);
        if ($this->formBrandId && $name !== '') {
            $model = AssetModel::firstOrCreate([
                'brand_id' => $this->formBrandId,
                'name' => $name,
            ]);
            $this->formModelId = $model->id;
            $this->newModelName = '';
            $this->showAddModel = false;
        }
    }

    public function markForVerification(): void
    {
        $warning = '[⚠️ VERIFICACIÓN PENDIENTE]: Marca y/o modelo no legibles a simple vista. Pendiente programar auditoría técnica de hardware o diagnóstico interno para confirmar placa base, serie real y fabricante.';
        if (empty(trim($this->formNotes))) {
            $this->formNotes = $warning;
        } elseif (! str_contains($this->formNotes, 'VERIFICACIÓN PENDIENTE')) {
            $this->formNotes = $warning."\n".$this->formNotes;
        }

        if (! $this->formBrandId) {
            $genericBrand = AssetBrand::where('name', 'like', '%GENÉRICO%')
                ->orWhere('name', 'like', '%DETERMINAR%')
                ->first();
            if ($genericBrand) {
                $this->formBrandId = $genericBrand->id;
                $genericModel = AssetModel::where('brand_id', $genericBrand->id)
                    ->where('name', 'like', '%VERIFICAR%')
                    ->first();
                if ($genericModel) {
                    $this->formModelId = $genericModel->id;
                }
            }
        }
    }

    protected function initializeSpecifications(?AssetCategory $cat): void
    {
        $this->formSpecifications = [];
        if ($cat && is_array($cat->custom_fields_schema)) {
            foreach ($cat->custom_fields_schema as $f) {
                $this->formSpecifications[$f['key']] = $f['defaultValue'] ?? ($f['type'] === 'boolean' ? ($f['defaultValue'] ?? false) : '');
            }
        }
    }

    public function openAssetModal(?int $id = null): void
    {
        $this->isDetailModalOpen = false;
        $this->editingAssetId = $id;
        $this->showAddBrand = false;
        $this->newBrandName = '';
        $this->showAddModel = false;
        $this->newModelName = '';

        if ($id) {
            $asset = Asset::find($id);
            if ($asset) {
                $this->formComputerCode = $asset->computer_code;
                $this->formPatrimonialCode = $asset->patrimonial_code ?? '';
                $this->formSerialNumber = $asset->serial_number ?? '';
                $this->formCategoryId = $asset->category_id;
                $this->formBrandId = $asset->brand_id;
                $this->formModelId = $asset->model_id;
                $this->formColor = $asset->color ?? '';
                $this->formAcquisitionDate = $asset->acquisition_date ?? '';
                $this->formStatus = $asset->status ?? 'OPERATIVO';
                $this->formPhysicalCondition = $asset->physical_condition ?? 'BUENO';
                $this->formOffice = $asset->office ?? 'Oficina de Desarrollo Tecnológico';
                $this->formAssignedPersonId = $asset->assigned_person_id;
                $person = $asset->assigned_person_id ? Person::find($asset->assigned_person_id) : null;
                $this->formAssignedPersonName = $person ? "{$person->full_name} ({$person->document_number})" : '';
                $this->formSupplier = $asset->supplier ?? '';
                $this->formWarrantyEndDate = $asset->warranty_end_date ?? '';
                $this->formIsLoanable = (bool) $asset->is_loanable;
                $this->formNotes = $asset->notes ?? '';
                $this->formSpecifications = is_array($asset->specifications) ? $asset->specifications : [];
            }
        } else {
            $firstCat = AssetCategory::where('code', 'LAP')->first() ?? AssetCategory::first();
            $this->formComputerCode = '';
            $this->formPatrimonialCode = '';
            $this->formSerialNumber = '';
            $this->formCategoryId = $firstCat?->id ?? 1;
            $this->formBrandId = null;
            $this->formModelId = null;
            $this->formColor = '';
            $this->formAcquisitionDate = now()->format('Y-m-d');
            $this->formStatus = 'OPERATIVO';
            $this->formPhysicalCondition = 'BUENO';
            $this->formOffice = 'Oficina de Desarrollo Tecnológico';
            $this->formAssignedPersonId = null;
            $this->formAssignedPersonName = '';
            $this->formSupplier = '';
            $this->formWarrantyEndDate = '';
            $this->formIsLoanable = false;
            $this->formNotes = '';
            $this->initializeSpecifications($firstCat);
        }
        $this->isAssetModalOpen = true;
    }

    public function saveAsset(): void
    {
        $this->validate([
            'formCategoryId' => 'required|integer',
            'formBrandId' => 'required|integer',
            'formModelId' => 'required|integer',
            'formStatus' => 'required|string',
            'formPhysicalCondition' => 'required|string',
        ], [
            'formBrandId.required' => 'Debe seleccionar una marca para el activo.',
            'formModelId.required' => 'Debe seleccionar un modelo para el activo.',
        ]);

        $this->resolveAssignedPersonFromInput($this->formAssignedPersonName);

        $category = AssetCategory::find($this->formCategoryId);
        $catCode = $category?->code ?? 'PC';

        if (! $this->editingAssetId) {
            $lastAsset = Asset::where('computer_code', 'like', "MDC-TI-{$catCode}-%")
                ->orderByRaw("CAST(SUBSTRING(computer_code FROM '[0-9]+$') AS INTEGER) DESC")
                ->first();

            if ($lastAsset && preg_match('/(\d+)$/', $lastAsset->computer_code, $matches)) {
                $nextNum = (int) $matches[1] + 1;
            } else {
                $nextNum = 1;
            }

            $computerCode = "MDC-TI-{$catCode}-".str_pad((string) $nextNum, 4, '0', STR_PAD_LEFT);
        } else {
            $computerCode = $this->formComputerCode;
        }

        $person = $this->formAssignedPersonId ? Person::find($this->formAssignedPersonId) : null;
        $assignedPersonName = $person ? ($person->first_name.' '.$person->paternal_surname.' '.$person->maternal_surname) : null;

        $data = [
            'computer_code' => $computerCode,
            'patrimonial_code' => $this->formPatrimonialCode ?: null,
            'serial_number' => $this->formSerialNumber ?: null,
            'category_id' => $this->formCategoryId,
            'brand_id' => $this->formBrandId ?: null,
            'model_id' => $this->formModelId ?: null,
            'color' => $this->formColor ?: null,
            'acquisition_date' => $this->formAcquisitionDate ?: null,
            'status' => $this->formStatus,
            'physical_condition' => $this->formPhysicalCondition,
            'office' => $this->formOffice ?: 'Oficina de Desarrollo Tecnológico',
            'assigned_person_id' => $this->formAssignedPersonId ?: null,
            'supplier' => $this->formSupplier ?: null,
            'warranty_end_date' => $this->formWarrantyEndDate ?: null,
            'is_loanable' => $this->formIsLoanable,
            'specifications' => $this->formSpecifications,
            'notes' => $this->formNotes ?: null,
        ];

        if ($this->editingAssetId) {
            $asset = Asset::find($this->editingAssetId);
            $asset?->update($data);
            $this->showAlert("Activo {$computerCode} actualizado exitosamente.");
        } else {
            Asset::create($data);
            $this->showAlert("Activo {$computerCode} registrado y codificado en el catálogo ITAM.");
        }

        $this->isAssetModalOpen = false;
    }

    public function openDetailModal(int $id): void
    {
        $this->selectedAssetForDetail = Asset::with([
            'category',
            'brand',
            'model',
            'assignedPerson',
            'childAssets.category',
            'childAssets.brand',
            'childAssets.model',
            'movements',
            'maintenanceOrders',
        ])->find($id);
        $this->isDetailModalOpen = true;
    }


    public function updatedFormOffice(string $value): void
    {
        if ($this->isSyncingOfficeAndPerson) {
            return;
        }

        $this->isSyncingOfficeAndPerson = true;
        try {
            $this->resolveCustodianFromOffice($value);
        } finally {
            $this->isSyncingOfficeAndPerson = false;
        }
    }

    protected function resolveCustodianFromOffice(?string $value): void
    {
        $clean = trim((string) $value);
        if ($clean === '') {
            return;
        }

        $office = null;

        // 1. Si viene con acrónimo entre corchetes ej. "[ALC] ALCALDÍA..."
        if (preg_match('/\[([^\]]+)\]/', $clean, $matches)) {
            $office = Office::where('acronym', $matches[1])->first();
        }

        // 2. Si no tiene corchetes, buscar por nombre exacto o parcial
        if (! $office) {
            $parts = explode('-', $clean);
            $searchName = trim($parts[0]);

            $office = Office::whereRaw('UPPER(name) = ?', [mb_strtoupper($searchName)])
                ->orWhereRaw('UPPER(name) LIKE ?', ['%' . mb_strtoupper($searchName) . '%'])
                ->first();
        }

        // 3. Intentar por palabras clave significativas si no hubo coincidencia directa
        if (! $office) {
            $words = array_values(array_filter(explode(' ', mb_strtoupper($clean)), fn ($w) => mb_strlen($w) >= 4));
            foreach ($words as $w) {
                if (in_array($w, ['MUNICIPAL', 'MUNICIPALIDAD', 'SEDE', 'PRINCIPAL', 'OFICINA', 'GERENCIA', 'SUBGERENCIA'])) {
                    continue;
                }
                $office = Office::whereRaw('UPPER(name) LIKE ?', ["%{$w}%"])->first();
                if ($office) {
                    break;
                }
            }
        }

        if ($office) {
            $resp = $office->resolveActiveResponsible();
            if (! empty($resp['person'])) {
                $person = $resp['person'];
                $this->formAssignedPersonId = $person->id;
                $this->formAssignedPersonName = "{$person->full_name} ({$person->document_number})";
            }
        }
    }

    public function updatedFormAssignedPersonName(string $value): void
    {
        if ($this->isSyncingOfficeAndPerson) {
            return;
        }

        $this->isSyncingOfficeAndPerson = true;
        try {
            $this->resolveAssignedPersonFromInput($value);

            // Si se identificó a la persona, cargar automáticamente su oficina
            if ($this->formAssignedPersonId) {
                $person = Person::find($this->formAssignedPersonId);
                if ($person) {
                    $this->resolveOfficeFromPerson($person);
                }
            }
        } finally {
            $this->isSyncingOfficeAndPerson = false;
        }
    }

    protected function resolveOfficeFromPerson(Person $person): void
    {
        if (empty($person->office)) {
            return;
        }

        $officeModel = null;

        // 1. Si la oficina de la persona tiene acrónimo entre corchetes ej. "[GAT]..."
        if (preg_match('/\[([^\]]+)\]/', $person->office, $matches)) {
            $officeModel = Office::where('acronym', $matches[1])->first();
        }

        // 2. Si no, buscar por nombre
        if (! $officeModel) {
            $cleanName = trim(preg_replace('/\[[^\]]+\]/', '', $person->office));
            $officeModel = Office::whereRaw('UPPER(name) = ?', [mb_strtoupper($cleanName)])
                ->orWhereRaw('UPPER(name) LIKE ?', ['%' . mb_strtoupper($cleanName) . '%'])
                ->first();
        }

        if ($officeModel) {
            $this->formOffice = "[{$officeModel->acronym}] {$officeModel->name} - {$officeModel->sede}";
        } else {
            $this->formOffice = $person->office;
        }
    }

    protected function resolveAssignedPersonFromInput(?string $value): void
    {
        $clean = trim((string) $value);

        if ($clean === '' || str_contains(mb_strtolower($clean), 'sin custodio')) {
            $this->formAssignedPersonId = null;

            return;
        }

        // 1. Extraer documento si viene en formato "... (45678912)" o "[45678912]"
        if (preg_match('/\((\d{8,11})\)/', $clean, $matches) || preg_match('/\[(\d{8,11})\]/', $clean, $matches)) {
            $person = Person::where('document_number', $matches[1])->first();
            if ($person) {
                $this->formAssignedPersonId = $person->id;

                return;
            }
        }

        // 2. Si se ingresó directamente un documento de identidad (DNI/RUC)
        if (ctype_digit($clean)) {
            $person = Person::where('document_number', $clean)->first();
            if ($person) {
                $this->formAssignedPersonId = $person->id;

                return;
            }
        }

        // 3. Búsqueda por coincidencia de nombre completo
        $all = Person::all();
        $match = $all->first(function ($p) use ($clean) {
            $fullName = mb_strtoupper($p->full_name);
            $cleanUpper = mb_strtoupper($clean);

            return $fullName === $cleanUpper
                || str_contains($fullName, $cleanUpper)
                || str_contains($cleanUpper, $fullName);
        });

        if ($match) {
            $this->formAssignedPersonId = $match->id;
        } else {
            $this->formAssignedPersonId = null;
        }
    }

    public function deleteAsset(int $id): void
    {
        $asset = Asset::find($id);
        if ($asset) {
            $code = $asset->computer_code;
            $asset->delete();
            $this->showAlert("Activo {$code} dado de baja (borrado suave con trazabilidad).");
        }
    }

    // ==========================================
    // MOVIMIENTOS & ACTAS
    // ==========================================
    public function toggleManualCustodian(): void
    {
        $this->manualCustodian = ! $this->manualCustodian;
    }

    public function updatedMovementAssetId(): void
    {
        $this->selectedMovementAsset = $this->movementAssetId
            ? Asset::with(['category', 'brand', 'model', 'assignedPerson', 'childAssets'])->find($this->movementAssetId)
            : null;
    }

    public function openMovementModal(?int $assetId = null): void
    {
        $this->isDetailModalOpen = false;
        $this->movementAssetId = $assetId;
        $this->movementAssetSearch = '';
        $this->selectedMovementAsset = $assetId
            ? Asset::with(['category', 'brand', 'model', 'assignedPerson', 'childAssets'])->find($assetId)
            : null;
        $this->movementType = 'TRASLADO';
        $this->movementToOffice = '';
        $this->movementTargetPersonId = null;
        $this->movementNewCustodian = '';
        $this->manualCustodian = false;
        $this->movementTechnician = 'Técnico de Soporte TI';
        $this->movementReason = '';
        $this->movementNotes = '';
        $this->movementIncludeChildren = true;
        $this->resetValidation();
        $this->isMovementModalOpen = true;
    }

    public function closeMovementModal(): void
    {
        $this->isMovementModalOpen = false;
        $this->selectedMovementAsset = null;
        $this->movementAssetId = null;
        $this->movementAssetSearch = '';
        $this->resetValidation();
    }

    public function getAvailableMovementAssetsProperty()
    {
        $query = Asset::with(['brand', 'model', 'assignedPerson'])
            ->where('is_active', true);

        if (! empty(trim($this->movementAssetSearch))) {
            $s = '%'.trim($this->movementAssetSearch).'%';
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

        if ($this->movementAssetId && ! $assets->contains('id', $this->movementAssetId)) {
            $selected = Asset::with(['brand', 'model', 'assignedPerson'])->find($this->movementAssetId);
            if ($selected) {
                $assets->prepend($selected);
            }
        }

        return $assets;
    }

    public function saveMovement(): void
    {
        $this->validate([
            'movementAssetId' => 'required|exists:itam_assets,id',
            'movementToOffice' => 'required|string|max:150',
            'movementReason' => 'required|string|max:500',
        ], [
            'movementAssetId.required' => 'Debe seleccionar el activo informático a trasladar o reasignar.',
            'movementToOffice.required' => 'La oficina / dependencia de destino es obligatoria.',
            'movementReason.required' => 'El motivo de la transferencia / justificación es obligatorio.',
        ]);

        $asset = Asset::with(['brand', 'model', 'childAssets', 'assignedPerson'])->find($this->movementAssetId);
        if (! $asset) {
            return;
        }

        // Generar correlativo oficial único para el acta (RF-10)
        $count = AssetMovement::count() + 1;
        $actaNumber = 'MDC-ACTA-MOV-'.date('Y').'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        while (AssetMovement::where('acta_number', $actaNumber)->exists()) {
            $count++;
            $actaNumber = 'MDC-ACTA-MOV-'.date('Y').'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        }

        // Determinar custodio receptor según modo (lista o manual)
        $toPersonId = $this->movementTargetPersonId;
        if ($this->manualCustodian && ! empty(trim($this->movementNewCustodian))) {
            $cleanName = trim($this->movementNewCustodian);
            $person = Person::whereRaw("CONCAT(first_name, ' ', paternal_surname, ' ', maternal_surname) ILIKE ?", ["%{$cleanName}%"])
                ->orWhere('document_number', $cleanName)
                ->first();
            $toPersonId = $person?->id;
        }

        $mov = AssetMovement::create([
            'acta_number' => $actaNumber,
            'asset_id' => $asset->id,
            'from_office' => $asset->office ?: 'Almacén Central TI',
            'to_office' => $this->movementToOffice,
            'from_person_id' => $asset->assigned_person_id,
            'to_person_id' => $toPersonId,
            'movement_date' => date('Y-m-d'),
            'movement_type' => $this->movementType,
            'reason' => $this->movementReason,
            'technician_name' => $this->movementTechnician ?: 'Técnico de Soporte TI',
            'notes' => $this->movementNotes,
            'include_children_in_transfer' => $this->movementIncludeChildren,
        ]);

        // Actualizar datos del activo trasladado
        $asset->office = $this->movementToOffice;
        if ($toPersonId) {
            $asset->assigned_person_id = $toPersonId;
        } elseif ($this->movementType === 'RETORNO') {
            $asset->assigned_person_id = null;
        } elseif (! $this->manualCustodian && ! $this->movementTargetPersonId) {
            $asset->assigned_person_id = null;
        }

        if ($this->movementType === 'BAJA') {
            $asset->status = 'BAJA';
        } elseif ($asset->status === 'BAJA') {
            $asset->status = 'OPERATIVO';
        }
        $asset->save();

        // Mover periféricos vinculados en lote conforme a RF-05
        if ($this->movementIncludeChildren && $asset->childAssets()->exists()) {
            $asset->childAssets()->update([
                'office' => $this->movementToOffice,
                'assigned_person_id' => $toPersonId,
            ]);
        }

        $this->isMovementModalOpen = false;
        $this->showAlert("Movimiento registrado con éxito. Acta oficial {$actaNumber} generada.");

        // Abrir automáticamente el acta oficial (RF-10)
        $this->openActaModal('TRANSFERENCIA', $asset, $mov);
    }

    public function openActaModal(string $type, $asset = null, $mov = null, $mnt = null, $loan = null): void
    {
        $this->isDetailModalOpen = false;
        $this->actaType = $type;

        $eagerRelations = [
            'category',
            'brand',
            'model',
            'assignedPerson',
            'childAssets.category',
            'childAssets.brand',
            'childAssets.model',
        ];

        $loadedAsset = null;
        if (is_numeric($asset)) {
            $loadedAsset = Asset::with($eagerRelations)->find((int) $asset);
        } elseif ($asset instanceof Asset) {
            $asset->loadMissing($eagerRelations);
            $loadedAsset = $asset;
        }

        $loadedMov = null;
        if (is_numeric($mov)) {
            $loadedMov = AssetMovement::with(['asset' => fn ($q) => $q->with($eagerRelations), 'fromPerson', 'toPerson'])->find((int) $mov);
        } elseif ($mov instanceof AssetMovement) {
            $mov->loadMissing(['asset' => fn ($q) => $q->with($eagerRelations), 'fromPerson', 'toPerson']);
            $loadedMov = $mov;
        }

        $loadedMnt = null;
        if (is_numeric($mnt)) {
            $loadedMnt = MaintenanceOrder::with(['asset' => fn ($q) => $q->with($eagerRelations), 'suppliesUsed.supply'])->find((int) $mnt);
        } elseif ($mnt instanceof MaintenanceOrder) {
            $mnt->loadMissing(['asset' => fn ($q) => $q->with($eagerRelations), 'suppliesUsed.supply']);
            $loadedMnt = $mnt;
        }

        $loadedLoan = null;
        if (is_numeric($loan)) {
            $loadedLoan = AssetLoan::with(['asset' => fn ($q) => $q->with($eagerRelations), 'requestingPerson', 'items.asset'])->find((int) $loan);
        } elseif ($loan instanceof AssetLoan) {
            $loan->loadMissing(['asset' => fn ($q) => $q->with($eagerRelations), 'requestingPerson', 'items.asset']);
            $loadedLoan = $loan;
        }

        $this->actaAsset = $loadedAsset ?? ($loadedMov?->asset ?? ($loadedMnt?->asset ?? ($loadedLoan?->asset ?? null)));
        if ($this->actaAsset) {
            $this->actaAsset->loadMissing($eagerRelations);
        }

        $this->actaMovement = $loadedMov;
        $this->actaMaintenance = $loadedMnt;
        $this->actaLoan = $loadedLoan;
        $this->isActaModalOpen = true;
    }

    // ==========================================
    // MANTENIMIENTOS
    // ==========================================
    public function openMaintenanceModal(?int $assetId = null): void
    {
        $this->maintenanceAssetId = $assetId ?? Asset::where('is_active', true)->first()?->id;
        $this->selectedMaintenanceAsset = $this->maintenanceAssetId
            ? Asset::with(['category', 'brand', 'model', 'assignedPerson'])->find($this->maintenanceAssetId)
            : null;
        $this->maintenanceType = 'PREVENTIVO';
        $this->maintenancePriority = 'MEDIA';
        $this->maintenanceScheduledDate = date('Y-m-d');
        $this->maintenanceTechnician = 'Técnico de Soporte TI';
        $this->maintenanceFailureReported = '';
        $this->maintenanceNotes = '';
        $this->resetValidation();
        $this->isMaintenanceModalOpen = true;
    }

    public function updatedMaintenanceAssetId(): void
    {
        $this->selectedMaintenanceAsset = $this->maintenanceAssetId
            ? Asset::with(['category', 'brand', 'model', 'assignedPerson'])->find($this->maintenanceAssetId)
            : null;
    }

    public function closeMaintenanceModal(): void
    {
        $this->isMaintenanceModalOpen = false;
        $this->selectedMaintenanceAsset = null;
        $this->resetValidation();
    }

    public function saveMaintenance(): void
    {
        $this->validate([
            'maintenanceAssetId' => 'required|exists:itam_assets,id',
            'maintenanceType' => 'required|string',
            'maintenancePriority' => 'required|string',
            'maintenanceScheduledDate' => 'required|string',
            'maintenanceTechnician' => 'required|string|max:150',
            'maintenanceFailureReported' => 'required_if:maintenanceType,CORRECTIVO|nullable|string|max:500',
        ], [
            'maintenanceAssetId.required' => 'Debe seleccionar el equipo informático.',
            'maintenanceFailureReported.required_if' => 'Debe detallar la falla reportada o síntomas para mantenimiento correctivo.',
        ]);

        $asset = Asset::find($this->maintenanceAssetId);
        if (! $asset) {
            return;
        }

        $count = MaintenanceOrder::count() + 1;
        $orderNumber = 'MNT-'.date('Y').'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        while (MaintenanceOrder::where('code', $orderNumber)->orWhere('order_number', $orderNumber)->exists()) {
            $count++;
            $orderNumber = 'MNT-'.date('Y').'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        }

        $diag = $this->maintenanceType === 'CORRECTIVO'
            ? $this->maintenanceFailureReported
            : 'Mantenimiento Preventivo Programado / Rutinario';

        $order = MaintenanceOrder::create([
            'code' => $orderNumber,
            'order_number' => $orderNumber,
            'asset_id' => $asset->id,
            'type' => $this->maintenanceType,
            'priority' => $this->maintenancePriority,
            'diagnosis' => $diag,
            'activities_performed' => $diag,
            'technician_name' => $this->maintenanceTechnician ?: 'Técnico de Soporte TI',
            'status' => 'PROGRAMADO',
            'scheduled_date' => $this->maintenanceScheduledDate ?: date('Y-m-d'),
            'notes' => $this->maintenanceNotes,
        ]);

        if ($this->maintenanceType === 'CORRECTIVO') {
            $asset->status = 'EN_MANTENIMIENTO';
            $asset->save();
        }

        $this->isMaintenanceModalOpen = false;
        $this->showAlert("Orden {$orderNumber} registrada y programada con éxito.");

        // Abrir automáticamente la Hoja de Servicio Oficial (RF-16)
        $this->openActaModal('MANTENIMIENTO', $asset, null, $order);
    }

    // ==========================================
    // INSUMOS
    // ==========================================
    public function openSupplyModal(?int $id = null): void
    {
        $this->editingSupplyId = $id;
        $this->supplyAdjustMode = false;
        $this->supplyDelta = 0;
        $this->supplyAdjustReason = '';

        if ($id) {
            $s = Supply::find($id);
            if ($s) {
                $this->formSupplyCode = $s->code;
                $this->formSupplyName = $s->name;
                $this->formSupplyCategory = $s->category;
                $this->formSupplyStock = $s->stock;
                $this->formSupplyMinStock = $s->min_stock;
                $this->formSupplyUnit = $s->unit;
                $this->formSupplyLocation = $s->location ?? 'Almacén ODT - Estante 1';
                $this->formSupplyNotes = $s->notes ?? ($s->compatible_models ?? '');
            }
        } else {
            $count = Supply::count() + 1;
            $this->formSupplyCode = 'INS-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
            $this->formSupplyName = '';
            $this->formSupplyCategory = 'TONER_TINTA';
            $this->formSupplyStock = 10;
            $this->formSupplyMinStock = 2;
            $this->formSupplyUnit = 'UNIDAD';
            $this->formSupplyLocation = 'Almacén ODT - Estante 1';
            $this->formSupplyNotes = '';
        }
        $this->isSupplyModalOpen = true;
    }

    public function saveSupply(): void
    {
        if ($this->supplyAdjustMode && $this->editingSupplyId) {
            $this->validate([
                'supplyDelta' => 'required|integer|not_in:0',
                'supplyAdjustReason' => 'required|string|min:3',
            ]);

            $s = Supply::find($this->editingSupplyId);
            if ($s) {
                $newStock = max(0, $s->stock + $this->supplyDelta);
                $s->stock = $newStock;
                $s->save();
                $this->showAlert("Stock de {$s->name} ajustado a {$newStock} {$s->unit}(s). Motivo: {$this->supplyAdjustReason}");
            }
            $this->isSupplyModalOpen = false;

            return;
        }

        $this->validate([
            'formSupplyName' => 'required|string|min:2',
            'formSupplyCategory' => 'required|string',
            'formSupplyUnit' => 'required|string',
            'formSupplyStock' => 'required|integer|min:0',
            'formSupplyMinStock' => 'required|integer|min:1',
        ]);

        if (! $this->formSupplyCode) {
            $count = Supply::count() + 1;
            $this->formSupplyCode = 'INS-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        }

        $data = [
            'code' => $this->formSupplyCode,
            'name' => $this->formSupplyName,
            'category' => $this->formSupplyCategory,
            'stock' => $this->formSupplyStock,
            'min_stock' => $this->formSupplyMinStock,
            'unit' => $this->formSupplyUnit,
            'location' => $this->formSupplyLocation ?: null,
            'notes' => $this->formSupplyNotes ?: null,
            'compatible_models' => $this->formSupplyNotes ?: null,
        ];

        if ($this->editingSupplyId) {
            Supply::find($this->editingSupplyId)?->update($data);
            $this->showAlert("Insumo {$this->formSupplyName} actualizado.");
        } else {
            Supply::create($data);
            $this->showAlert("Insumo {$this->formSupplyName} agregado al almacén.");
        }

        $this->isSupplyModalOpen = false;
    }

    public function adjustStock(int $supplyId, int $delta): void
    {
        $s = Supply::find($supplyId);
        if ($s) {
            $s->stock = max(0, $s->stock + $delta);
            $s->save();
            $this->showAlert("Stock de {$s->name} actualizado a {$s->stock} {$s->unit}s.");
        }
    }

    // ==========================================
    // PRÉSTAMOS
    // ==========================================
    public function openLoanModal(?int $assetId = null, ?int $loanId = null): void
    {
        $this->editingLoanId = $loanId;
        $this->loanReturnCondition = 'BUENO';
        $this->loanReturnNotes = '';

        if ($loanId) {
            $loan = AssetLoan::with('items')->find($loanId);
            if ($loan) {
                $this->editingLoanNumber = $loan->loan_number ?? $loan->loan_code;
                $this->loanDepartment = $loan->requesting_office ?? '';
                $this->loanBorrowerName = $loan->borrower_name ?? '';
                $this->loanStartDate = $loan->start_date ? substr($loan->start_date, 0, 10) : date('Y-m-d');
                $this->loanExpectedReturnDate = $loan->expected_return_date ? substr($loan->expected_return_date, 0, 10) : date('Y-m-d', strtotime('+2 days'));
                $this->loanReason = $loan->event_reason ?? '';
                $this->loanNotes = $loan->notes ?? '';
                $this->loanSelectedAssetIds = $loan->items->pluck('asset_id')->toArray();
            }
        } else {
            $this->editingLoanNumber = null;
            $this->loanDepartment = '';
            $this->loanBorrowerName = '';
            $this->loanStartDate = date('Y-m-d');
            $this->loanExpectedReturnDate = date('Y-m-d', strtotime('+2 days'));
            $this->loanReason = '';
            $this->loanNotes = '';
            if ($assetId) {
                $this->loanSelectedAssetIds = [$assetId];
            } else {
                $firstLoanable = Asset::where('is_loanable', true)->first();
                $this->loanSelectedAssetIds = $firstLoanable ? [$firstLoanable->id] : [];
            }
        }

        $this->isLoanModalOpen = true;
    }

    public function toggleLoanAsset(int $assetId): void
    {
        if (in_array($assetId, $this->loanSelectedAssetIds, true)) {
            $this->loanSelectedAssetIds = array_values(array_diff($this->loanSelectedAssetIds, [$assetId]));
        } else {
            $this->loanSelectedAssetIds[] = $assetId;
        }
    }

    public function openReturnLoanModal(int $loanId): void
    {
        $this->openLoanModal(null, $loanId);
    }

    public function saveLoan(): void
    {
        if ($this->editingLoanId) {
            $loan = AssetLoan::with('items.asset')->find($this->editingLoanId);
            if ($loan) {
                $loan->update([
                    'status' => 'DEVUELTO',
                    'actual_return_date' => now()->toDateString(),
                    'return_condition' => $this->loanReturnCondition,
                    'notes' => $this->loanReturnNotes ? ($loan->notes ? $loan->notes."\n[Retorno]: ".$this->loanReturnNotes : $this->loanReturnNotes) : $loan->notes,
                ]);

                foreach ($loan->items as $item) {
                    $item->asset?->update(['status' => 'OPERATIVO']);
                }

                $this->showAlert("Préstamo {$loan->loan_number} devuelto conforme.");
                $this->isLoanModalOpen = false;

                $firstAssetId = $loan->items->first()?->asset_id;
                $this->openActaModal('PRESTAMO', $firstAssetId, null, null, $loan->id);
            }

            return;
        }

        $this->validate([
            'loanDepartment' => 'required|string|min:2',
            'loanBorrowerName' => 'required|string|min:2',
            'loanStartDate' => 'required|string',
            'loanExpectedReturnDate' => 'required|string',
            'loanReason' => 'required|string|min:3',
        ]);

        $loanableExists = Asset::where('is_loanable', true)->exists();
        if ($loanableExists && empty($this->loanSelectedAssetIds)) {
            $this->addError('loanSelectedAssetIds', 'Debe seleccionar al menos un equipo para el préstamo.');

            return;
        }

        $count = AssetLoan::count() + 1;
        $loanCode = 'LOAN-'.date('Y').'-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);
        $loanNumber = 'PR-'.date('Y').'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);

        $firstAssetId = ! empty($this->loanSelectedAssetIds) ? $this->loanSelectedAssetIds[0] : Asset::first()?->id;

        $loan = AssetLoan::create([
            'loan_code' => $loanCode,
            'loan_number' => $loanNumber,
            'requesting_office' => $this->loanDepartment,
            'borrower_name' => $this->loanBorrowerName,
            'start_date' => $this->loanStartDate,
            'expected_return_date' => $this->loanExpectedReturnDate,
            'event_reason' => $this->loanReason,
            'status' => 'ACTIVO',
            'notes' => $this->loanNotes,
            'technician_name' => 'Soporte ODT',
        ]);

        if (! empty($this->loanSelectedAssetIds)) {
            foreach ($this->loanSelectedAssetIds as $aid) {
                AssetLoanItem::create([
                    'loan_id' => $loan->id,
                    'asset_id' => (int) $aid,
                ]);
                Asset::where('id', $aid)->update(['status' => 'PRESTADO']);
            }
        } elseif ($firstAssetId) {
            AssetLoanItem::create([
                'loan_id' => $loan->id,
                'asset_id' => (int) $firstAssetId,
            ]);
            Asset::where('id', $firstAssetId)->update(['status' => 'PRESTADO']);
        }

        $this->isLoanModalOpen = false;
        $this->showAlert("Préstamo {$loanNumber} registrado exitosamente.");

        // Abrir automáticamente el acta oficial (RF-20)
        $this->openActaModal('PRESTAMO', $firstAssetId, null, null, $loan->id);
    }

    // ==========================================
    // AUDITORÍA ANUAL & SNAPSHOT (RF-11)
    // ==========================================
    // ==========================================
    // SOFTWARE & LICENCIAS (RF-02)
    // ==========================================
    public function openSoftwareModal(?int $id = null): void
    {
        $this->editingSoftwareId = $id;

        if ($id) {
            $sw = Software::find($id);
            if ($sw) {
                $this->formSoftwareName = $sw->name;
                $this->formSoftwareVersion = $sw->version ?? '';
                $this->formSoftwareDeveloper = $sw->developer ?? '';
                $this->formSoftwareLicenseType = $sw->license_type ?? 'OEM';
                $this->formSoftwareTotalLicenses = $sw->total_licenses ?? 1;
                $this->formSoftwareLicenseKey = $sw->license_key ?? '';
                $this->formSoftwareExpirationDate = $sw->expiration_date;
                $this->formSoftwareNotes = $sw->notes ?? '';
            }
        } else {
            $this->formSoftwareName = '';
            $this->formSoftwareVersion = '';
            $this->formSoftwareDeveloper = '';
            $this->formSoftwareLicenseType = 'OEM';
            $this->formSoftwareTotalLicenses = 1;
            $this->formSoftwareLicenseKey = '';
            $this->formSoftwareExpirationDate = null;
            $this->formSoftwareNotes = '';
        }

        $this->isSoftwareModalOpen = true;
    }

    public function saveSoftware(): void
    {
        $this->validate([
            'formSoftwareName' => 'required|string|min:2|max:150',
            'formSoftwareLicenseType' => 'required|string',
            'formSoftwareTotalLicenses' => 'required|integer|min:1',
        ]);

        $data = [
            'name' => $this->formSoftwareName,
            'version' => $this->formSoftwareVersion ?: null,
            'developer' => $this->formSoftwareDeveloper ?: null,
            'license_type' => $this->formSoftwareLicenseType,
            'license_key' => $this->formSoftwareLicenseKey ?: null,
            'total_licenses' => $this->formSoftwareTotalLicenses,
            'expiration_date' => $this->formSoftwareExpirationDate ?: null,
            'notes' => $this->formSoftwareNotes ?: null,
            'is_active' => true,
        ];

        if ($this->editingSoftwareId) {
            Software::find($this->editingSoftwareId)?->update($data);
            $this->showAlert("Software {$this->formSoftwareName} actualizado.");
        } else {
            Software::create($data);
            $this->showAlert("Software {$this->formSoftwareName} registrado en el catálogo.");
        }

        $this->isSoftwareModalOpen = false;
    }

    public function openAuditModal(): void
    {
        $year = (int) date('Y');
        $this->formAuditYear = $year;
        $this->formAuditTitle = "Inventario Físico Anual {$year}";
        $this->formAuditNotes = '';
        $this->isAuditModalOpen = true;
    }

    public function saveAudit(): void
    {
        $this->validate([
            'formAuditYear' => 'required|integer|min:2020|max:2050',
            'formAuditTitle' => 'required|string|min:3|max:150',
        ]);

        $assets = Asset::with(['category', 'brand', 'model', 'assignedPerson'])
            ->where('status', '!=', 'BAJA')
            ->get();

        $snapshot = $assets->map(function ($a) {
            return [
                'id' => $a->id,
                'computer_code' => $a->computer_code,
                'patrimonial_code' => $a->patrimonial_code,
                'serial_number' => $a->serial_number,
                'category' => $a->category?->name,
                'brand' => $a->brand?->name,
                'model' => $a->model?->name,
                'office' => $a->office,
                'custodian' => $a->assignedPerson?->full_name,
                'status' => $a->status,
            ];
        })->toArray();

        $audit = InventoryAudit::create([
            'year' => $this->formAuditYear,
            'title' => $this->formAuditTitle,
            'status' => 'EN_PROCESO',
            'start_date' => now()->toDateString(),
            'snapshot_data' => $snapshot,
            'notes' => $this->formAuditNotes ?: null,
        ]);

        foreach ($assets as $a) {
            AuditVerification::create([
                'audit_id' => $audit->id,
                'asset_id' => $a->id,
                'expected_office' => $a->office ?: 'Almacén ODT',
                'found_office' => null,
                'status' => 'PENDIENTE',
                'verified_by' => null,
                'notes' => null,
            ]);
        }

        $this->isAuditModalOpen = false;
        $this->showAlert("Auditoría '{$this->formAuditTitle}' aperturada con snapshot de {$assets->count()} activos.");
    }

    public function openAuditReconciliationModal(int $auditId): void
    {
        $this->selectedAuditForModal = InventoryAudit::with([
            'verifications.asset.category',
            'verifications.asset.brand',
            'verifications.asset.model',
        ])->find($auditId);
        $this->auditScanCode = '';
        $this->auditFoundOffice = '';
        $this->auditVerificationStatus = 'CONCILIADO';
        $this->auditFilterStatus = 'ALL';
        $this->isAuditReconciliationModalOpen = true;
    }

    public function registerAuditVerification(): void
    {
        if (! $this->selectedAuditForModal || ! trim($this->auditScanCode)) {
            return;
        }

        $code = trim(strtoupper($this->auditScanCode));
        $verification = $this->selectedAuditForModal->verifications->first(function ($v) use ($code) {
            return strtoupper((string) $v->asset?->computer_code) === $code
                || (string) $v->asset?->patrimonial_code === $code
                || (string) $v->asset_id === $code;
        });

        if (! $verification) {
            $this->showAlert("El activo '{$code}' no pertenece a este inventario o no existe.", 'error');

            return;
        }

        $verification->update([
            'found_office' => $this->auditFoundOffice ?: $verification->expected_office,
            'status' => $this->auditVerificationStatus,
            'verified_by' => 'Técnico de Control Patrimonial',
        ]);

        $this->openAuditReconciliationModal($this->selectedAuditForModal->id);
        $this->showAlert("Activo {$verification->asset?->computer_code} conciliado como {$this->auditVerificationStatus}.");
        $this->auditScanCode = '';
        $this->auditFoundOffice = '';
    }

    public function quickVerifyAsset(int $verificationId): void
    {
        $v = AuditVerification::with('asset')->find($verificationId);
        if ($v) {
            $v->update([
                'found_office' => $v->expected_office,
                'status' => 'CONCILIADO',
                'verified_by' => 'Técnico de Control Patrimonial',
            ]);
            if ($this->selectedAuditForModal) {
                $this->openAuditReconciliationModal($this->selectedAuditForModal->id);
            }
            $this->showAlert("Activo {$v->asset?->computer_code} marcado como CONCILIADO.");
        }
    }

    public function closeAudit(int $auditId): void
    {
        $audit = InventoryAudit::find($auditId);
        if ($audit) {
            $audit->update([
                'status' => 'CERRADO',
                'close_date' => now()->toDateString(),
            ]);
            if ($this->selectedAuditForModal && $this->selectedAuditForModal->id === $auditId) {
                $this->openAuditReconciliationModal($auditId);
            }
            $this->showAlert("Inventario anual '{$audit->title}' cerrado definitivamente.");
        }
    }

    public function returnLoan(int $loanId): void
    {
        $this->openReturnLoanModal($loanId);
    }

    // ==========================================
    // MARCAS Y MODELOS CRUD (SRS RF-01)
    // ==========================================
    public function openBrandModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->catalogType = 'brand';
        $this->editingBrandId = $id;
        if ($id) {
            $brand = AssetBrand::find($id);
            if ($brand) {
                $this->formBrandName = $brand->name;
                $this->formBrandDescription = $brand->description ?? '';
                $this->formBrandIsActive = (bool) $brand->is_active;
            }
        } else {
            $this->formBrandName = '';
            $this->formBrandDescription = '';
            $this->formBrandIsActive = true;
        }
        $this->isBrandModelModalOpen = true;
    }

    public function openModelModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->catalogType = 'model';
        $this->editingModelId = $id;
        if ($id) {
            $model = AssetModel::find($id);
            if ($model) {
                $this->formModelName = $model->name;
                $this->formModelBrandId = $model->brand_id;
                $this->formModelCategoryId = $model->category_id;
                $this->formModelDescription = $model->description ?? '';
                $this->formModelIsActive = (bool) $model->is_active;
            }
        } else {
            $this->formModelName = '';
            $this->formModelBrandId = AssetBrand::first()?->id;
            $this->formModelCategoryId = null;
            $this->formModelDescription = '';
            $this->formModelIsActive = true;
        }
        $this->isBrandModelModalOpen = true;
    }

    public function saveBrandOrModel(): void
    {
        if ($this->catalogType === 'brand') {
            $nameRule = $this->editingBrandId
                ? ['required', 'string', 'min:2', 'max:100', Rule::unique('itam_asset_brands', 'name')->ignore($this->editingBrandId)]
                : ['required', 'string', 'min:2', 'max:100', Rule::unique('itam_asset_brands', 'name')];

            $this->validate([
                'formBrandName' => $nameRule,
                'formBrandDescription' => ['nullable', 'string', 'max:500'],
            ], [
                'formBrandName.required' => 'El nombre de la marca es obligatorio.',
                'formBrandName.unique' => 'Ya existe una marca con este nombre.',
            ]);

            if ($this->editingBrandId) {
                $brand = AssetBrand::findOrFail($this->editingBrandId);
                $brand->update([
                    'name' => trim($this->formBrandName),
                    'description' => trim($this->formBrandDescription) ?: null,
                    'is_active' => $this->formBrandIsActive,
                ]);
                $this->showAlert("Marca '{$brand->name}' actualizada exitosamente.");
            } else {
                $brand = AssetBrand::create([
                    'name' => trim($this->formBrandName),
                    'description' => trim($this->formBrandDescription) ?: null,
                    'is_active' => true,
                ]);
                $this->showAlert("Marca '{$brand->name}' registrada exitosamente.");
            }
        } else {
            $this->validate([
                'formModelBrandId' => ['required', 'integer', 'exists:itam_asset_brands,id'],
                'formModelCategoryId' => ['nullable', 'integer', 'exists:itam_asset_categories,id'],
                'formModelName' => ['required', 'string', 'min:2', 'max:120'],
                'formModelDescription' => ['nullable', 'string', 'max:500'],
            ], [
                'formModelBrandId.required' => 'Debe seleccionar una marca fabricante.',
                'formModelName.required' => 'El nombre del modelo es obligatorio.',
            ]);

            if ($this->editingModelId) {
                $model = AssetModel::findOrFail($this->editingModelId);
                $model->update([
                    'name' => trim($this->formModelName),
                    'brand_id' => $this->formModelBrandId,
                    'category_id' => $this->formModelCategoryId,
                    'description' => trim($this->formModelDescription) ?: null,
                    'is_active' => $this->formModelIsActive,
                ]);
                $this->showAlert("Modelo '{$model->name}' actualizado exitosamente.");
            } else {
                $model = AssetModel::create([
                    'name' => trim($this->formModelName),
                    'brand_id' => $this->formModelBrandId,
                    'category_id' => $this->formModelCategoryId,
                    'description' => trim($this->formModelDescription) ?: null,
                    'is_active' => true,
                ]);
                $this->showAlert("Modelo '{$model->name}' registrado exitosamente.");
            }
        }

        $this->isBrandModelModalOpen = false;
    }

    public function deleteBrand(int $id): void
    {
        $brand = AssetBrand::withCount(['models', 'assets'])->find($id);
        if (! $brand) {
            return;
        }

        if ($brand->assets_count > 0) {
            $this->showAlert("No se puede eliminar la marca '{$brand->name}' porque tiene {$brand->assets_count} activo(s) vinculados.", 'error');

            return;
        }

        if ($brand->models_count > 0) {
            $this->showAlert("No se puede eliminar la marca '{$brand->name}' porque tiene {$brand->models_count} modelo(s) registrados.", 'error');

            return;
        }

        $name = $brand->name;
        $brand->delete();
        $this->showAlert("Marca '{$name}' eliminada correctamente.");
    }

    public function deleteModel(int $id): void
    {
        $model = AssetModel::withCount('assets')->find($id);
        if (! $model) {
            return;
        }

        if ($model->assets_count > 0) {
            $this->showAlert("No se puede eliminar el modelo '{$model->name}' porque tiene {$model->assets_count} activo(s) vinculados.", 'error');

            return;
        }

        $name = $model->name;
        $model->delete();
        $this->showAlert("Modelo '{$name}' eliminado correctamente.");
    }

    // ==========================================
    // PERIFÉRICOS & COMPONENTES HIJOS
    // ==========================================
    public function openChildModal(int $parentId): void
    {
        $this->isDetailModalOpen = false;
        $this->parentForChildModal = Asset::with(['childAssets', 'category', 'brand', 'model'])->find($parentId);
        $this->selectedChildAssetId = null;
        $this->isChildModalOpen = true;
    }

    public function linkChildAsset(): void
    {
        if (! $this->parentForChildModal || ! $this->selectedChildAssetId) {
            return;
        }

        $child = Asset::find($this->selectedChildAssetId);
        if ($child) {
            $child->parent_asset_id = $this->parentForChildModal->id;
            $child->office = $this->parentForChildModal->office;
            $child->assigned_person_id = $this->parentForChildModal->assigned_person_id;
            $child->save();

            $this->showAlert("Periférico {$child->computer_code} vinculado a {$this->parentForChildModal->computer_code}.");
            $this->parentForChildModal->load('childAssets');
            $this->selectedChildAssetId = null;
        }
    }

    public function unlinkChildAsset(int $childId): void
    {
        $child = Asset::find($childId);
        if ($child) {
            $child->parent_asset_id = null;
            $child->save();

            $this->showAlert("Componente {$child->computer_code} desvinculado.");
            if ($this->parentForChildModal) {
                $this->parentForChildModal->load('childAssets');
            }
        }
    }

    // ==========================================
    // ESQUEMAS DINÁMICOS POR CATEGORÍA
    // ==========================================
    // ==========================================
    // GESTIÓN DE CATEGORÍAS TECNOLÓGICAS (SRS RF-01)
    // ==========================================
    public function openCategoryModal(?int $id = null): void
    {
        $this->resetCategoryForm();
        if ($id) {
            $cat = AssetCategory::find($id);
            if ($cat) {
                $this->editingCategoryId = $cat->id;
                $this->formCatName = $cat->name;
                $this->formCatCode = $cat->code;
                $this->formCatDescription = $cat->description ?? '';
                $this->formCatIcon = $cat->icon ?? '💻';
                $this->formCatColor = $cat->color ?? '#2563eb';
            }
        }
        $this->isCategoryModalOpen = true;
    }

    public function resetCategoryForm(): void
    {
        $this->resetValidation();
        $this->editingCategoryId = null;
        $this->formCatName = '';
        $this->formCatCode = '';
        $this->formCatDescription = '';
        $this->formCatIcon = '💻';
        $this->formCatColor = '#2563eb';
    }

    public function updatedFormCatName($value): void
    {
        if (! $this->editingCategoryId && empty($this->formCatCode)) {
            $words = preg_split('/\s+/', trim((string) $value));
            if (count($words) >= 2) {
                $this->formCatCode = strtoupper(substr($words[0], 0, 2).substr($words[1], 0, 1));
            } elseif (! empty($words[0]) && strlen($words[0]) >= 3) {
                $this->formCatCode = strtoupper(substr($words[0], 0, 3));
            }
        }
    }

    public function updatedFormCatCode($value): void
    {
        $this->formCatCode = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $value));
    }

    public function saveCategory(): void
    {
        $codeRule = $this->editingCategoryId
            ? ['required', 'string', 'max:20', Rule::unique('itam_asset_categories', 'code')->ignore($this->editingCategoryId)]
            : ['required', 'string', 'max:20', Rule::unique('itam_asset_categories', 'code')];

        $this->validate([
            'formCatName' => ['required', 'string', 'max:100'],
            'formCatCode' => $codeRule,
            'formCatIcon' => ['required', 'string', 'max:30'],
            'formCatColor' => ['required', 'string', 'max:20'],
            'formCatDescription' => ['nullable', 'string', 'max:500'],
        ], [
            'formCatName.required' => 'El nombre de la categoría es obligatorio.',
            'formCatCode.required' => 'El código de prefijo es obligatorio.',
            'formCatCode.unique' => 'Ya existe una categoría con este prefijo correlativo.',
        ]);

        if ($this->editingCategoryId) {
            $cat = AssetCategory::findOrFail($this->editingCategoryId);
            $cat->update([
                'name' => trim($this->formCatName),
                'description' => trim($this->formCatDescription) ?: null,
                'icon' => $this->formCatIcon,
                'color' => $this->formCatColor,
            ]);
            $this->showAlert("Categoría '{$cat->name}' actualizada exitosamente.");
        } else {
            $cat = AssetCategory::create([
                'name' => trim($this->formCatName),
                'code' => strtoupper(trim($this->formCatCode)),
                'description' => trim($this->formCatDescription) ?: null,
                'icon' => $this->formCatIcon,
                'color' => $this->formCatColor,
                'custom_fields_schema' => [],
                'is_active' => true,
            ]);
            $this->showAlert("Categoría '{$cat->name}' creada exitosamente.");
        }

        $this->isCategoryModalOpen = false;
        $this->resetCategoryForm();
    }

    public function openCategorySchemaModal(int $categoryId): void
    {
        $this->categoryForSchema = AssetCategory::find($categoryId);
        $rawFields = $this->categoryForSchema?->custom_fields_schema
            ?? $this->categoryForSchema?->custom_fields
            ?? [];

        $this->schemaFields = [];
        $this->schemaPreviewValues = [];
        $this->schemaTab = 'editor';
        $this->schemaErrorMsg = null;

        foreach ($rawFields as $f) {
            $id = (string) Str::uuid();
            $key = $f['key'] ?? ($f['name'] ?? '');
            $options = $f['options'] ?? [];
            $optionsStr = is_array($options) ? implode(', ', $options) : (string) $options;
            $this->schemaFields[$id] = [
                '_id' => $id,
                'key' => $key,
                'name' => $key,
                'label' => $f['label'] ?? $key,
                'type' => $f['type'] ?? 'text',
                'required' => (bool) ($f['required'] ?? false),
                'placeholder' => $f['placeholder'] ?? '',
                'unit' => $f['unit'] ?? '',
                'options' => is_array($options) ? $options : [],
                'options_str' => $optionsStr,
            ];
            $this->schemaPreviewValues[$key] = $f['default'] ?? '';
        }

        $this->isCategorySchemaModalOpen = true;
    }

    public function addSchemaField(): void
    {
        $id = (string) Str::uuid();
        $newIndex = count($this->schemaFields) + 1;
        $this->schemaFields[$id] = [
            '_id' => $id,
            'key' => 'parametro_'.$newIndex,
            'name' => 'parametro_'.$newIndex,
            'label' => 'Nuevo Parámetro '.$newIndex,
            'type' => 'text',
            'required' => false,
            'placeholder' => '',
            'unit' => '',
            'options' => [],
            'options_str' => '',
        ];
        $this->schemaErrorMsg = null;
    }

    public function removeSchemaField(string|int $id): void
    {
        if (isset($this->schemaFields[$id])) {
            unset($this->schemaFields[$id]);
        } else {
            $keys = array_keys($this->schemaFields);
            if (isset($keys[$id])) {
                unset($this->schemaFields[$keys[$id]]);
            }
        }
        $this->schemaErrorMsg = null;
    }

    public function reorderSchemaField(string|int $from, string|int $to): void
    {
        $keys = array_keys($this->schemaFields);
        $fromIndex = is_numeric($from) && isset($keys[(int) $from]) ? (int) $from : array_search($from, $keys, true);
        $toIndex = is_numeric($to) && isset($keys[(int) $to]) ? (int) $to : array_search($to, $keys, true);

        if ($fromIndex === false || $toIndex === false || $fromIndex === $toIndex) {
            return;
        }

        $item = array_splice($keys, $fromIndex, 1);
        array_splice($keys, $toIndex, 0, $item);

        $reordered = [];
        foreach ($keys as $k) {
            $reordered[$k] = $this->schemaFields[$k];
        }
        $this->schemaFields = $reordered;
        $this->schemaErrorMsg = null;
    }

    public function moveSchemaField(string|int $id, string $direction): void
    {
        $keys = array_keys($this->schemaFields);
        $index = is_numeric($id) && isset($keys[(int) $id]) ? (int) $id : array_search($id, $keys, true);
        if ($index === false) {
            return;
        }

        $targetIndex = $direction === 'up' ? $index - 1 : $index + 1;
        if ($targetIndex < 0 || $targetIndex >= count($keys)) {
            return;
        }

        $item = array_splice($keys, $index, 1);
        array_splice($keys, $targetIndex, 0, $item);

        $reordered = [];
        foreach ($keys as $k) {
            $reordered[$k] = $this->schemaFields[$k];
        }
        $this->schemaFields = $reordered;
        $this->schemaErrorMsg = null;
    }

    public function saveCategorySchema(): void
    {
        $this->schemaErrorMsg = null;

        $usedKeys = [];
        foreach ($this->schemaFields as $i => $field) {
            $num = count($usedKeys) + 1;
            $key = trim((string) ($field['key'] ?? ''));
            $label = trim((string) ($field['label'] ?? ''));

            if (empty($key)) {
                $this->schemaErrorMsg = "El parámetro #{$num} no tiene una clave técnica (key) definida.";

                return;
            }
            if (empty($label)) {
                $this->schemaErrorMsg = "El parámetro #{$num} no tiene una etiqueta descriptiva.";

                return;
            }

            $keyLower = strtolower($key);
            if (in_array($keyLower, $usedKeys, true)) {
                $this->schemaErrorMsg = "La clave técnica '{$key}' está duplicada. Cada parámetro debe tener una clave única.";

                return;
            }
            $usedKeys[] = $keyLower;
        }

        $cleanSchema = [];
        foreach ($this->schemaFields as $field) {
            $key = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower(trim((string) $field['key'])));
            $options = [];
            if (! empty($field['options_str'])) {
                $options = array_values(array_filter(array_map('trim', explode(',', (string) $field['options_str']))));
            } elseif (! empty($field['options'])) {
                $options = is_array($field['options']) ? array_values($field['options']) : [];
            }

            $cleanSchema[] = [
                'key' => $key,
                'name' => $key,
                'label' => trim((string) $field['label']),
                'type' => $field['type'] ?? 'text',
                'required' => (bool) ($field['required'] ?? false),
                'placeholder' => trim((string) ($field['placeholder'] ?? '')),
                'unit' => trim((string) ($field['unit'] ?? '')),
                'options' => $options,
            ];
        }

        if ($this->categoryForSchema) {
            $this->categoryForSchema->custom_fields_schema = $cleanSchema;
            $this->categoryForSchema->custom_fields = $cleanSchema;
            $this->categoryForSchema->save();

            $this->showAlert("Esquema técnico para '{$this->categoryForSchema->name}' guardado exitosamente con ".count($cleanSchema).' parámetros.');
            $this->isCategorySchemaModalOpen = false;
        }
    }

    public function render(GetItamDashboardMetricsAction $metricsAction): View
    {
        // 1. Activos filtrados
        $assetsQuery = Asset::with(['category', 'brand', 'model', 'assignedPerson', 'childAssets', 'parentAsset'])
            ->when($this->search, function ($q) {
                $s = '%'.trim($this->search).'%';
                $q->where(function ($sub) use ($s) {
                    $sub->where('computer_code', 'ILIKE', $s)
                        ->orWhere('patrimonial_code', 'ILIKE', $s)
                        ->orWhere('serial_number', 'ILIKE', $s)
                        ->orWhere('office', 'ILIKE', $s)
                        ->orWhereHas('assignedPerson', function ($ap) use ($s) {
                            $ap->where('first_name', 'ILIKE', $s)
                                ->orWhere('last_name', 'ILIKE', $s);
                        })
                        ->orWhereHas('brand', function ($b) use ($s) {
                            $b->where('name', 'ILIKE', $s);
                        })
                        ->orWhereHas('model', function ($m) use ($s) {
                            $m->where('name', 'ILIKE', $s);
                        });
                });
            })
            ->when($this->selectedCategory, fn ($q) => $q->where('category_id', $this->selectedCategory))
            ->when($this->selectedStatus, fn ($q) => $q->where('status', $this->selectedStatus))
            ->when($this->selectedBrand, fn ($q) => $q->where('brand_id', $this->selectedBrand))
            ->orderBy('id', 'desc');

        $assets = $assetsQuery->paginate($this->perPage);

        // 2. Colecciones auxiliares
        $categories = AssetCategory::orderBy('name')->get();
        $allBrands = AssetBrand::orderBy('name')->get();
        $brands = AssetBrand::withCount('models')
            ->when($this->searchBrand, function ($q) {
                $s = '%'.trim($this->searchBrand).'%';
                $q->where(fn ($sub) => $sub->where('name', 'ILIKE', $s)->orWhere('description', 'ILIKE', $s));
            })
            ->orderBy('name')
            ->get();
        $models = AssetModel::with(['brand', 'category'])
            ->when($this->searchModel, function ($q) {
                $s = '%'.trim($this->searchModel).'%';
                $q->where(fn ($sub) => $sub->where('name', 'ILIKE', $s)->orWhere('description', 'ILIKE', $s));
            })
            ->when($this->filterModelBrandId, fn ($q) => $q->where('brand_id', $this->filterModelBrandId))
            ->orderBy('name')
            ->get();
        $offices = Office::orderBy('name')->get();
        $movements = AssetMovement::with(['asset.brand', 'asset.model', 'fromPerson', 'toPerson'])->latest('id')->take(20)->get();
        $maintenanceOrders = MaintenanceOrder::with(['asset.brand', 'asset.model'])->latest('id')->take(20)->get();
        $supplies = Supply::orderBy('name')->get();
        $criticalSupplies = Supply::whereRaw('stock <= min_stock')->get();
        $loans = AssetLoan::with(['asset.brand', 'asset.model', 'requestingPerson', 'items.asset.brand', 'items.asset.model'])->latest('id')->take(20)->get();
        $audits = InventoryAudit::with('verifications')->latest('id')->get();
        $softwareList = Software::withCount('assets')->orderBy('name')->get();
        $metrics = $metricsAction->handle();

        $counts = [
            'assets' => Asset::count(),
            'movements' => AssetMovement::count(),
            'maintenance' => MaintenanceOrder::count(),
            'supplies' => Supply::count(),
            'critical_supplies' => $criticalSupplies->count(),
            'loans' => AssetLoan::count(),
            'delayed_loans' => AssetLoan::where('status', 'ACTIVO')->where('expected_return_date', '<', now())->count(),
            'audits' => InventoryAudit::count(),
            'software' => Software::count(),
            'categories' => $categories->count(),
            'brands' => $brands->count(),
            'models' => $models->count(),
        ];

        $candidateChildAssets = Asset::where('status', 'OPERATIVO')
            ->whereNull('parent_asset_id')
            ->when($this->parentForChildModal, fn ($q) => $q->where('id', '!=', $this->parentForChildModal->id))
            ->orderBy('computer_code')
            ->get();

        $loanableAssets = Asset::with(['category', 'brand', 'model'])
            ->where('is_loanable', true)
            ->where('status', '!=', 'BAJA')
            ->orderBy('computer_code')
            ->get();

        return view('livewire.itam.dashboard', [
            'loanableAssets' => $loanableAssets,
            'assets' => $assets,
            'categories' => $categories,
            'allBrands' => $allBrands,
            'brands' => $brands,
            'models' => $models,
            'people' => Person::orderBy('paternal_surname')->get(),
            'municipalOffices' => $this->municipalOffices,
            'offices' => $offices,
            'movements' => $movements,
            'maintenanceOrders' => $maintenanceOrders,
            'supplies' => $supplies,
            'criticalSupplies' => $criticalSupplies,
            'loans' => $loans,
            'audits' => $audits,
            'softwareList' => $softwareList,
            'metrics' => $metrics,
            'counts' => $counts,
            'candidateChildAssets' => $candidateChildAssets,
        ])->layout('components.layouts.subsystem', [
            'title' => 'Inventario y Gestión Activos TI - SIGM Castilla',
            'moduleName' => 'Inventario y Gestión TI',
            'subsystem' => 'itam',
            'activeTab' => $this->activeTab,
            'counts' => $counts,
        ]);
    }
}
