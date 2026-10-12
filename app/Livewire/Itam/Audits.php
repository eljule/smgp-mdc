<?php

declare(strict_types=1);

namespace App\Livewire\Itam;

use App\Actions\Itam\GetAuditsCatalogAction;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Auditorías de Inventario Físico - SIGM Castilla',
    'moduleName' => 'Inventario y Gestión TI',
    'subsystem' => 'itam',
    'activeTab' => 'audits',
])]
#[Title('Auditorías de Inventario Físico - SIGM Castilla')]
class Audits extends Component
{
    use WithPagination;

    #[Url(as: 'buscar')]
    public string $search = '';

    public int $perPage = 10;

    public function render(GetAuditsCatalogAction $action): View
    {
        $audits = $action->handle(search: $this->search, perPage: $this->perPage);

        return view('livewire.itam.audits', [
            'audits' => $audits,
        ]);
    }
}
