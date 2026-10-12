<?php

declare(strict_types=1);

namespace App\Livewire\Helpdesk;

use App\Models\Helpdesk\Ticket;
use App\Models\Helpdesk\TicketAuditLog;
use App\Models\Itam\Asset;
use App\Models\Office;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.ticket-portal', [
    'title' => 'Registrar Falla en su Puesto de Trabajo - SIGM Castilla',
])]
#[Title('Registrar Falla en su Puesto de Trabajo - SIGM Castilla')]
class NewTicket extends Component
{
    use WithFileUploads;

    // 1. Datos de Identificación (RF-02)
    public string $applicantName = 'ADMINISTRADOR DEL SISTEMA SIGM';

    public string $officeName = 'OFICINA DE REGIDORES';

    public string $applicantPhone = 'Anexo 104';

    public string $applicantEmail = 'admin@castilla.gob.pe';

    // 2. Escaneo de Código QR (RF-03)
    public string $qrCodeInput = '';

    public ?int $selectedAssetId = null;

    public ?string $selectedAssetCode = null;

    public ?string $selectedAssetCategory = null;

    public ?string $selectedAssetName = null;

    public ?string $assetLookupError = null;

    // 3. Formulario de Incidencia (RF-04)
    public string $category = 'HARDWARE';

    public string $priority = 'MEDIA';

    public string $subject = '';

    public string $description = '';

    public $photo = null;

    public ?string $evidencePhotoUrl = null;

    // Estado del envío
    public bool $submitting = false;

    public ?string $createdTicketNumber = null;

    public ?string $errorMessage = null;

    public function mount(?string $asset = null): void
    {
        $user = auth()->user();
        if ($user) {
            $this->applicantName = $user->name ?? $user->username ?? 'ADMINISTRADOR DEL SISTEMA SIGM';
            $this->applicantEmail = $user->email ?? 'admin@castilla.gob.pe';
        }

        $assetCode = $asset ?? request()->query('asset');
        if (! empty($assetCode)) {
            $this->qrCodeInput = (string) $assetCode;
            $this->lookupAsset();
        }
    }

    public function autoDetect(): void
    {
        $this->applicantName = 'ADMINISTRADOR DEL SISTEMA SIGM';
        $this->officeName = 'OFICINA DE REGIDORES';
        $this->applicantPhone = 'Anexo 104';
        $this->applicantEmail = 'admin@castilla.gob.pe';
    }

    public function lookupAsset(): void
    {
        $this->assetLookupError = null;
        $code = trim($this->qrCodeInput);

        if (empty($code)) {
            $this->assetLookupError = 'Ingrese un código patrimonial, informático o serie.';

            return;
        }

        $asset = Asset::with(['category', 'assignedPerson'])
            ->where('computer_code', 'like', "%{$code}%")
            ->orWhere('patrimonial_code', 'like', "%{$code}%")
            ->orWhere('serial_number', 'like', "%{$code}%")
            ->first();

        if ($asset) {
            $this->selectedAssetId = $asset->id;
            $this->selectedAssetCode = $asset->computer_code;
            $this->selectedAssetCategory = $asset->category?->name ?? 'Hardware TI';
            $this->selectedAssetName = "{$asset->computer_code} - {$this->selectedAssetCategory}";

            if (! empty($asset->office)) {
                $this->officeName = $asset->office;
            }

            if ($asset->assignedPerson) {
                $this->applicantName = $asset->assignedPerson->first_name.' '.$asset->assignedPerson->last_name;
            }
        } else {
            $this->assetLookupError = 'No se encontró ningún activo tecnológico con este código.';
            $this->selectedAssetId = null;
            $this->selectedAssetCode = null;
            $this->selectedAssetCategory = null;
            $this->selectedAssetName = null;
        }
    }

    public function clearAsset(): void
    {
        $this->selectedAssetId = null;
        $this->selectedAssetCode = null;
        $this->selectedAssetCategory = null;
        $this->selectedAssetName = null;
        $this->qrCodeInput = '';
        $this->assetLookupError = null;
    }

    public function setCategory(string $cat): void
    {
        $this->category = $cat;
    }

    public function setPriority(string $prio): void
    {
        $this->priority = $prio;
    }

    public function saveTicket(): void
    {
        $this->errorMessage = null;

        if (empty(trim($this->subject)) || empty(trim($this->description))) {
            $this->errorMessage = 'Por favor complete el asunto y la descripción detallada del inconveniente.';

            return;
        }

        if (empty(trim($this->applicantName))) {
            $this->errorMessage = 'Por favor ingrese el nombre del solicitante.';

            return;
        }

        $count = Ticket::count() + 1;
        $ticketNumber = 'TCK-'.date('Y').'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);

        $office = Office::where('name', $this->officeName)->first();

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'applicant_name' => $this->applicantName,
            'applicant_phone' => $this->applicantPhone,
            'applicant_email' => $this->applicantEmail,
            'office_id' => $office?->id,
            'office_name' => $this->officeName ?: 'Oficina Municipal',
            'asset_id' => $this->selectedAssetId,
            'asset_computer_code' => $this->selectedAssetCode ?: ($this->qrCodeInput ?: null),
            'asset_category' => $this->selectedAssetCategory,
            'category' => $this->category,
            'priority' => $this->priority,
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => 'ABIERTO',
        ]);

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'previous_status' => null,
            'new_status' => 'ABIERTO',
            'action' => 'TICKET_REGISTRADO',
            'performed_by_user_name' => $this->applicantName,
            'notes' => 'Ticket registrado desde el portal directo de incidencias TI.',
        ]);

        $this->createdTicketNumber = $ticket->ticket_number;
    }

    public function resetForm(): void
    {
        $this->createdTicketNumber = null;
        $this->subject = '';
        $this->description = '';
        $this->photo = null;
        $this->clearAsset();
        $this->errorMessage = null;
    }

    public function render(): View
    {
        $offices = Office::orderBy('name')->get();

        return view('livewire.helpdesk.new-ticket', [
            'offices' => $offices,
        ]);
    }
}
