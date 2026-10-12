<?php

declare(strict_types=1);

namespace App\Livewire\Itam;

use App\Actions\Itam\GetSoftwareCatalogAction;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Licencias de Software Institucional - SIGM Castilla',
    'moduleName' => 'Inventario y Gestión TI',
    'subsystem' => 'itam',
    'activeTab' => 'software',
])]
#[Title('Licencias de Software Institucional - SIGM Castilla')]
class Software extends Component
{
    use WithPagination;

    #[Url(as: 'buscar')]
    public string $search = '';

    public int $perPage = 10;

    public function render(GetSoftwareCatalogAction $action): View
    {
        $softwareList = $action->handle(search: $this->search, perPage: $this->perPage);

        return view('livewire.itam.software', [
            'softwareList' => $softwareList,
        ]);
    }
}
