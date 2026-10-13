<?php

declare(strict_types=1);

namespace App\Livewire\Itam;

use App\Actions\Itam\GetSuppliesCatalogAction;
use App\Models\Itam\Supply;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Almacén de Insumos TI - SIGM Castilla',
    'moduleName' => 'Inventario y Gestión TI',
    'subsystem' => 'itam',
    'activeTab' => 'supplies',
])]
#[Title('Almacén de Insumos TI - SIGM Castilla')]
class Supplies extends Component
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
    public string $category = 'TODAS';

    public int $perPage = 10;

    public bool $showCreateModal = false;

    public string $formCode = '';

    public string $formName = '';

    public string $formCategory = 'TONER_TINTA';

    public int $formStock = 0;

    public int $formMinStock = 2;

    public string $formUnit = 'UNIDAD';

    public float $formUnitCost = 0.00;

    public string $formLocation = '';

    public string $formCompatibleModels = '';

    public string $formNotes = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->formCode = 'INS-'.str_pad((string) (Supply::count() + 1), 3, '0', STR_PAD_LEFT);
        $this->formName = '';
        $this->formStock = 5;
        $this->formMinStock = 2;
        $this->formUnit = 'UNIDAD';
        $this->formUnitCost = 0.00;
        $this->formLocation = '';
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->flashMessage = "Insumo '{$this->formName}' registrado y actualizado en almacén.";
    }

    public function saveSupply(): void
    {
        $this->validate([
            'formCode' => 'required|string|max:50',
            'formName' => 'required|string|max:150',
        ]);

        Supply::updateOrCreate(
            ['code' => $this->formCode],
            [
                'name' => $this->formName,
                'category' => $this->formCategory,
                'stock' => $this->formStock,
                'min_stock' => $this->formMinStock,
                'unit' => $this->formUnit,
                'unit_cost' => $this->formUnitCost,
                'location' => $this->formLocation ?: null,
                'compatible_models' => $this->formCompatibleModels ?: null,
                'notes' => $this->formNotes ?: null,
            ]
        );

        $this->showCreateModal = false;
        $this->flashMessage = "Insumo '{$this->formName}' registrado y actualizado en almacén.";
    }

    public function adjustStock(int $supplyId, int $delta): void
    {
        $supply = Supply::find($supplyId);
        if ($supply) {
            $supply->stock = max(0, $supply->stock + $delta);
            $supply->save();
            $this->flashMessage = "Stock de '{$supply->name}' actualizado a {$supply->stock} {$supply->unit}(s).";
        }
    }

    public function render(GetSuppliesCatalogAction $action): View
    {
        $supplies = $action->handle(
            search: $this->search,
            category: $this->category,
            perPage: $this->perPage
        );

        return view('livewire.itam.supplies', [
            'supplies' => $supplies,
        ]);
    }
}
