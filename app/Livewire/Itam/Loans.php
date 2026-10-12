<?php

declare(strict_types=1);

namespace App\Livewire\Itam;

use App\Actions\Itam\GetLoansCatalogAction;
use App\Models\Itam\Asset;
use App\Models\Itam\AssetLoan;
use App\Models\Itam\AssetLoanItem;
use App\Models\Office;
use App\Models\Person;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Préstamos y Reservas de Equipos - SIGM Castilla',
    'moduleName' => 'Inventario y Gestión TI',
    'subsystem' => 'itam',
    'activeTab' => 'loans',
])]
#[Title('Préstamos y Reservas de Equipos - SIGM Castilla')]
class Loans extends Component
{
    use WithPagination;

    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'estado')]
    public string $status = 'TODOS';

    public int $perPage = 10;

    public bool $showCreateModal = false;

    public string $formRequestingOffice = '';

    public ?int $formRequestingPersonId = null;

    public string $formBorrowerName = '';

    public string $formStartDate = '';

    public string $formExpectedReturnDate = '';

    public string $formEventReason = '';

    public array $formSelectedAssetIds = [];

    public function openCreateModal(): void
    {
        $this->formRequestingOffice = '';
        $this->formBorrowerName = '';
        $this->formStartDate = date('Y-m-d H:i');
        $this->formExpectedReturnDate = date('Y-m-d H:i', strtotime('+1 day'));
        $this->formEventReason = '';
        $this->formSelectedAssetIds = [];
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function saveLoan(): void
    {
        $this->validate([
            'formRequestingOffice' => 'required|string',
            'formBorrowerName' => 'required|string',
            'formEventReason' => 'required|string',
            'formSelectedAssetIds' => 'required|array|min:1',
        ]);

        $loanCode = 'PREST-'.date('Y').'-'.str_pad((string) (AssetLoan::count() + 1), 4, '0', STR_PAD_LEFT);

        $loan = AssetLoan::create([
            'loan_code' => $loanCode,
            'loan_number' => $loanCode,
            'requesting_office' => $this->formRequestingOffice,
            'requesting_person_id' => $this->formRequestingPersonId,
            'borrower_name' => $this->formBorrowerName,
            'start_date' => $this->formStartDate,
            'expected_return_date' => $this->formExpectedReturnDate,
            'event_reason' => $this->formEventReason,
            'status' => 'ENTREGADO',
            'technician_name' => 'Soporte TI',
        ]);

        foreach ($this->formSelectedAssetIds as $assetId) {
            AssetLoanItem::create([
                'loan_id' => $loan->id,
                'asset_id' => (int) $assetId,
            ]);

            // Update asset status
            $asset = Asset::find($assetId);
            if ($asset) {
                $asset->status = 'PRESTADO';
                $asset->save();
            }
        }

        $this->showCreateModal = false;
    }

    public function returnLoan(int $loanId): void
    {
        $loan = AssetLoan::with('items')->find($loanId);
        if ($loan) {
            $loan->status = 'DEVUELTO';
            $loan->actual_return_date = date('Y-m-d H:i');
            $loan->save();

            foreach ($loan->items as $item) {
                $asset = Asset::find($item->asset_id);
                if ($asset) {
                    $asset->status = 'OPERATIVO';
                    $asset->save();
                }
            }
        }
    }

    public function render(GetLoansCatalogAction $action): View
    {
        $loans = $action->handle(search: $this->search, status: $this->status, perPage: $this->perPage);
        $offices = Office::where('is_active', true)->orderBy('name')->get();
        $people = Person::where('is_active', true)->where('type', 'PERSONAL')->orderBy('first_name')->take(60)->get();
        $availableAssets = Asset::with(['brand', 'model', 'category'])->where('is_loanable', true)->where('status', 'OPERATIVO')->get();

        return view('livewire.itam.loans', [
            'loans' => $loans,
            'offices' => $offices,
            'people' => $people,
            'availableAssets' => $availableAssets,
        ]);
    }
}
