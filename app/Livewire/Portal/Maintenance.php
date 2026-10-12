<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\CoreModule;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Subsistema en Mantenimiento Programado - SIGM Castilla')]
class Maintenance extends Component
{
    #[Url(as: 'code')]
    public string $code = 'transportes';

    public bool $isChecking = false;

    public ?string $statusNotice = null;

    public function checkStatus(): void
    {
        $this->isChecking = true;

        // Simular verificación técnica con el servidor
        $module = CoreModule::where('code', $this->code)->first();

        if ($module && $module->status !== 'maintenance') {
            $this->statusNotice = '¡El subsistema ha restablecido sus operaciones! Ya puede ingresar con normalidad.';
        } else {
            $this->statusNotice = 'Las tareas de mantenimiento en los servidores de la ODT continúan en progreso. Por favor espere el tiempo estimado.';
        }

        $this->isChecking = false;
    }

    public function render(): View
    {
        $module = CoreModule::where('code', $this->code)->first();

        $title = $module ? $module->name : 'Subsistema en Mantenimiento';
        $message = $module && $module->status_message
            ? $module->status_message
            : 'Este subsistema se encuentra temporalmente suspendido por trabajos de mantenimiento programado y optimización de base de datos.';
        $waitTime = $module && $module->wait_time ? $module->wait_time : 'Hasta nuevo aviso';
        $route = $module ? $module->route : '/portal';
        $color = $module && $module->accent_color ? $module->accent_color : '#f59e0b';

        return view('livewire.portal.maintenance', [
            'module' => $module,
            'title' => $title,
            'message' => $message,
            'waitTime' => $waitTime,
            'route' => $route,
            'color' => $color,
        ]);
    }
}
