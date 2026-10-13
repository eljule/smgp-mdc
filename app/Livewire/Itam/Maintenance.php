<?php

declare(strict_types=1);

namespace App\Livewire\Itam;

use App\Actions\Itam\GetMaintenanceCatalogAction;
use App\Models\Itam\Asset;
use App\Models\Itam\MaintenanceOrder;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Órdenes de Mantenimiento TI - SIGM Castilla',
    'moduleName' => 'Inventario y Gestión TI',
    'subsystem' => 'itam',
    'activeTab' => 'maintenance',
])]
#[Title('Órdenes de Mantenimiento TI - SIGM Castilla')]
class Maintenance extends Component
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

    #[Url(as: 'estado')]
    public string $status = 'TODOS';

    public int $perPage = 10;

    public bool $showCreateModal = false;

    public ?int $formAssetId = null;

    public string $formType = 'PREVENTIVO';

    public string $formPriority = 'MEDIA';

    public string $formScheduledDate = '';

    public string $formTechnicianName = 'Ing. Carlos Mendoza (ODT)';

    public string $formDiagnosis = '';

    public function openCreateModal(): void
    {
        $this->formAssetId = null;
        $this->formType = 'PREVENTIVO';
        $this->formPriority = 'MEDIA';
        $this->formScheduledDate = date('Y-m-d');
        $this->formDiagnosis = '';
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->flashMessage = "Orden de mantenimiento {$code} programada y registrada con éxito.";
    }

    public function saveOrder(): void
    {
        $this->validate([
            'formAssetId' => 'required|exists:itam_assets,id',
            'formScheduledDate' => 'required|string',
        ]);

        $code = 'OT-MNT-'.date('Y').'-'.str_pad((string) (MaintenanceOrder::count() + 1), 4, '0', STR_PAD_LEFT);

        MaintenanceOrder::create([
            'code' => $code,
            'order_number' => $code,
            'asset_id' => $this->formAssetId,
            'type' => $this->formType,
            'status' => 'PROGRAMADO',
            'priority' => $this->formPriority,
            'scheduled_date' => $this->formScheduledDate,
            'technician_name' => $this->formTechnicianName,
            'diagnosis' => $this->formDiagnosis ?: null,
        ]);

        $this->showCreateModal = false;
        $this->flashMessage = "Orden de mantenimiento {$code} programada y registrada con éxito.";
    }

    public function completeOrder(int $orderId): void
    {
        $order = MaintenanceOrder::find($orderId);
        if ($order) {
            $order->status = 'COMPLETADO';
            $order->completed_date = date('Y-m-d');
            $order->activities_performed = 'Mantenimiento preventivo completado: limpieza de chasis, cambio de pasta térmica y diagnóstico de software.';
            $order->save();
            $this->flashMessage = "Orden {$order->code} marcada como completada en el historial técnico.";
        }
    }

    public function render(GetMaintenanceCatalogAction $action): View
    {
        $orders = $action->handle(search: $this->search, status: $this->status, perPage: $this->perPage);
        $assets = Asset::with(['brand', 'model', 'category'])->where('is_active', true)->orderBy('computer_code')->get();

        return view('livewire.itam.maintenance', [
            'orders' => $orders,
            'assets' => $assets,
        ]);
    }
}
