<?php

declare(strict_types=1);

namespace App\Livewire\Transportes;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.subsystem', [
    'title' => 'Licencias de Transportes - SIGM Castilla',
    'moduleName' => 'Licencias de Transportes',
    'subsystem' => 'transportes',
    'activeTab' => 'dashboard',
])]
#[Title('Licencias de Transportes - SIGM Castilla')]
class Dashboard extends Component
{
    public function render(): View
    {
        return view('livewire.transportes.dashboard');
    }
}
