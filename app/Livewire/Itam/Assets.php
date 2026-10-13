<?php

declare(strict_types=1);

namespace App\Livewire\Itam;

use App\Actions\Itam\GetAssetsCatalogAction;
use App\Models\Itam\Asset;
use App\Models\Itam\AssetBrand;
use App\Models\Itam\AssetCategory;
use App\Models\Itam\AssetModel;
use App\Models\Office;
use App\Models\Person;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Catálogo de Activos Tecnológicos - SIGM Castilla',
    'moduleName' => 'Inventario y Gestión TI',
    'subsystem' => 'itam',
    'activeTab' => 'assets',
])]
#[Title('Catálogo de Activos Tecnológicos - SIGM Castilla')]
class Assets extends Component
{
    use WithPagination;

    public ?string $flashMessage = null;

    public string $flashType = 'success';

    public function hydrate(): void
    {
        $this->flashMessage = null;
    }

    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'categoria')]
    public string $categoryCode = 'TODAS';

    #[Url(as: 'estado')]
    public string $status = 'TODOS';

    public int $perPage = 10;

    // Modal state
    public bool $showCreateModal = false;

    public bool $showDetailModal = false;

    public ?int $selectedAssetId = null;

    // Form inputs
    public string $formComputerCode = '';

    public string $formPatrimonialCode = '';

    public string $formSerialNumber = '';

    public ?int $formCategoryId = null;

    public ?int $formBrandId = null;

    public ?int $formModelId = null;

    public string $formColor = '';

    public string $formStatus = 'OPERATIVO';

    public string $formPhysicalCondition = 'BUENO';

    public string $formOffice = '';

    public ?int $formAssignedPersonId = null;

    public string $formSupplier = '';

    public string $formWarrantyEndDate = '';

    public bool $formIsLoanable = false;

    public string $formNotes = '';

    public array $formSpecifications = [];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryCode(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->formComputerCode = 'MDC-TI-'.str_pad((string) (Asset::count() + 1), 4, '0', STR_PAD_LEFT);
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function openDetailModal(int $assetId): void
    {
        $this->selectedAssetId = $assetId;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedAssetId = null;
    }

    public function updatedFormCategoryId(?int $categoryId): void
    {
        if ($categoryId) {
            $cat = AssetCategory::find($categoryId);
            if ($cat && ! empty($cat->custom_fields_schema)) {
                $this->formSpecifications = [];
                foreach ($cat->custom_fields_schema as $field) {
                    $this->formSpecifications[$field['key']] = '';
                }
            }
        }
    }

    public function saveAsset(): void
    {
        $this->validate([
            'formComputerCode' => 'required|string|max:60',
            'formCategoryId' => 'required|exists:itam_asset_categories,id',
            'formBrandId' => 'required|exists:itam_asset_brands,id',
            'formModelId' => 'required|exists:itam_asset_models,id',
        ]);

        Asset::updateOrCreate(
            ['computer_code' => $this->formComputerCode],
            [
                'patrimonial_code' => $this->formPatrimonialCode ?: null,
                'serial_number' => $this->formSerialNumber ?: null,
                'category_id' => $this->formCategoryId,
                'brand_id' => $this->formBrandId,
                'model_id' => $this->formModelId,
                'color' => $this->formColor ?: null,
                'status' => $this->formStatus,
                'physical_condition' => $this->formPhysicalCondition,
                'office' => $this->formOffice ?: null,
                'assigned_person_id' => $this->formAssignedPersonId,
                'supplier' => $this->formSupplier ?: null,
                'warranty_end_date' => $this->formWarrantyEndDate ?: null,
                'is_loanable' => $this->formIsLoanable,
                'specifications' => $this->formSpecifications,
                'notes' => $this->formNotes ?: null,
                'is_active' => true,
            ]
        );

        $this->showCreateModal = false;
        $this->flashMessage = 'Activo tecnológico registrado y codificado en el catálogo ITAM.';
    }

    private function resetForm(): void
    {
        $this->formComputerCode = '';
        $this->formPatrimonialCode = '';
        $this->formSerialNumber = '';
        $this->formCategoryId = null;
        $this->formBrandId = null;
        $this->formModelId = null;
        $this->formColor = '';
        $this->formStatus = 'OPERATIVO';
        $this->formPhysicalCondition = 'BUENO';
        $this->formOffice = '';
        $this->formAssignedPersonId = null;
        $this->formSupplier = '';
        $this->formWarrantyEndDate = '';
        $this->formIsLoanable = false;
        $this->formNotes = '';
        $this->formSpecifications = [];
    }

    public function render(GetAssetsCatalogAction $action): View
    {
        $assets = $action->handle(
            search: $this->search,
            categoryCode: $this->categoryCode,
            status: $this->status,
            perPage: $this->perPage
        );

        $selectedAsset = $this->selectedAssetId ? Asset::with(['category', 'brand', 'model', 'assignedPerson', 'movements', 'maintenanceOrders', 'software'])->find($this->selectedAssetId) : null;
        $categories = AssetCategory::where('is_active', true)->orderBy('name')->get();
        $brands = AssetBrand::where('is_active', true)->orderBy('name')->get();
        $models = $this->formBrandId ? AssetModel::where('brand_id', $this->formBrandId)->where('is_active', true)->orderBy('name')->get() : collect();
        $people = Person::where('is_active', true)->where('type', 'PERSONAL')->orderBy('first_name')->take(60)->get();
        $offices = Office::where('is_active', true)->orderBy('name')->get();

        return view('livewire.itam.assets', [
            'assets' => $assets,
            'selectedAsset' => $selectedAsset,
            'categories' => $categories,
            'brands' => $brands,
            'models' => $models,
            'people' => $people,
            'offices' => $offices,
        ]);
    }
}
