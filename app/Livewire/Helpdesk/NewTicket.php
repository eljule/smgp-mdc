<?php

declare(strict_types=1);

namespace App\Livewire\Helpdesk;

use App\Actions\Admin\GetOfficesCatalogAction;
use App\Actions\Admin\GetPeopleCatalogAction;
use App\Models\Helpdesk\Ticket;
use App\Models\Helpdesk\TicketAuditLog;
use App\Models\Itam\Asset;
use App\Models\Office;
use App\Models\Person;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.ticket-portal', [
    'title' => 'Mesa de Ayuda y Soporte TI - Municipalidad Distrital de Castilla',
])]
#[Title('Mesa de Ayuda y Soporte TI - Municipalidad Distrital de Castilla')]
class NewTicket extends Component
{
    use WithFileUploads;

    // 1. Dependencia y Responsable de la Oficina Requirente
    public string $officeName = 'OFICINA DE REGIDORES';

    public string $applicantName = 'JULIO ERNESTO CHAVEZ CRISANTO';

    public string $applicantPosition = 'ESPECIALISTA TECNOLÓGICO';

    public string $applicantPhone = 'Anexo 102';

    public string $applicantEmail = 'jchavez@castilla.gob.pe';

    // 2. Escaneo de Código QR o Activo (Opcional)
    public string $qrCodeInput = '';

    public ?int $selectedAssetId = null;

    public ?string $selectedAssetCode = null;

    public ?string $selectedAssetCategory = null;

    public ?string $selectedAssetName = null;

    public ?string $assetLookupError = null;

    // 3. Formulario de Incidencia
    public string $category = 'HARDWARE';

    public string $priority = 'MEDIA';

    public string $subject = '';

    public string $description = '';

    public $photo = null;

    public ?string $evidencePhotoUrl = null;

    // Historial y Consulta de la Oficina (Columna Derecha)
    public string $historySearch = '';

    public ?int $selectedHistoryTicketId = null;

    public ?Ticket $selectedHistoryTicket = null;

    // Estado del envío
    public bool $submitting = false;

    public ?string $createdTicketNumber = null;

    public ?string $errorMessage = null;

    public ?string $flashMessage = null;

    public string $flashType = 'success';

    public function hydrate(): void
    {
        $this->flashMessage = null;
    }

    public function mount(?string $asset = null): void
    {
        $user = auth()->user();
        if ($user) {
            $this->applicantName = $user->name ?? $user->username ?? 'ADMINISTRADOR DEL SISTEMA SIGM';
            $this->applicantEmail = $user->email ?? 'admin@castilla.gob.pe';
        }

        $office = request()->query('oficina') ?? request()->query('office');
        if (! empty($office)) {
            $this->officeName = trim((string) $office);
        }

        $dni = request()->query('dni');
        if (! empty($dni)) {
            $dniStr = trim((string) $dni);
            $person = Person::where('document_number', $dniStr)->first();
            if ($person) {
                $this->applicantName = $person->full_name;
                $this->applicantPosition = $person->position ?: 'Servidor Municipal';
                if (empty($office) && ! empty($person->office)) {
                    $this->officeName = $person->office;
                }
                if (! empty($person->email)) {
                    $this->applicantEmail = $person->email;
                }
                if (! empty($person->phone)) {
                    $this->applicantPhone = $person->phone;
                }
            } else {
                foreach (GetPeopleCatalogAction::PEOPLE_DATA as $p) {
                    if (($p['documentNumber'] ?? '') === $dniStr) {
                        $this->applicantName = $p['fullName'];
                        $this->applicantPosition = $p['position'] ?? 'Servidor Municipal';
                        if (empty($office) && ! empty($p['office'])) {
                            $this->officeName = $p['office'];
                        }
                        if (! empty($p['email'])) {
                            $this->applicantEmail = $p['email'];
                        }
                        if (! empty($p['phone'])) {
                            $this->applicantPhone = $p['phone'];
                        }
                        break;
                    }
                }
            }
        }

        $assetCode = $asset ?? request()->query('asset');
        if (! empty($assetCode)) {
            $this->qrCodeInput = (string) $assetCode;
            $this->lookupAsset();
        }
    }

    public function updatedOfficeName(?string $val = null): void
    {
        $office = trim((string) ($val ?? $this->officeName));
        if (empty($office)) {
            return;
        }

        // Buscar si existe un responsable registrado para esta oficina
        $person = Person::where('office', $office)
            ->orWhere('office', 'like', "%{$office}%")
            ->first();

        if ($person) {
            $this->applicantName = $person->full_name;
            $this->applicantPosition = $person->position ?: 'Responsable de Oficina';
            if (! empty($person->phone)) {
                $this->applicantPhone = $person->phone;
            }
            if (! empty($person->email)) {
                $this->applicantEmail = $person->email;
            }

            return;
        }

        foreach (GetPeopleCatalogAction::PEOPLE_DATA as $p) {
            if (str_contains(strtoupper((string) ($p['office'] ?? '')), strtoupper($office))) {
                $this->applicantName = $p['fullName'];
                $this->applicantPosition = $p['position'] ?? 'Responsable de Oficina';
                if (! empty($p['phone'])) {
                    $this->applicantPhone = $p['phone'];
                }
                if (! empty($p['email'])) {
                    $this->applicantEmail = $p['email'];
                }

                return;
            }
        }
    }

    public function autoDetect(): void
    {
        $this->applicantName = 'JULIO ERNESTO CHAVEZ CRISANTO';
        $this->officeName = 'OFICINA DE REGIDORES';
        $this->applicantPosition = 'ESPECIALISTA TECNOLÓGICO';
        $this->applicantPhone = 'Anexo 102';
        $this->applicantEmail = 'jchavez@castilla.gob.pe';
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

    public function viewHistoryTicket(int $id): void
    {
        $this->selectedHistoryTicketId = $id;
        $this->selectedHistoryTicket = Ticket::with(['technicalDetail'])->find($id);
    }

    public function closeHistoryTicket(): void
    {
        $this->selectedHistoryTicketId = null;
        $this->selectedHistoryTicket = null;
    }

    public function saveTicket(): void
    {
        $this->errorMessage = null;

        if (empty(trim($this->subject)) || empty(trim($this->description))) {
            $this->errorMessage = 'Por favor complete el asunto y la descripción detallada del inconveniente.';

            return;
        }

        if (empty(trim($this->applicantName))) {
            $this->errorMessage = 'Por favor ingrese el nombre del solicitante o responsable.';

            return;
        }

        $count = Ticket::count() + 1;
        $ticketNumber = 'INC-'.date('Y').'-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);

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
        $this->flashMessage = "Ticket {$ticket->ticket_number} registrado exitosamente. Su reporte fue enviado a Soporte TI.";

        // Limpiar campos de la incidencia manteniendo la oficina
        $this->subject = '';
        $this->description = '';
        $this->photo = null;
        $this->clearAsset();
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
        if ($offices->isEmpty()) {
            $catalogAction = new GetOfficesCatalogAction;
            $offices = collect($catalogAction->execute())->map(fn ($o) => (object) [
                'id' => $o['id'],
                'name' => $o['name'],
                'acronym' => $o['sigla'],
            ]);
        }

        $cleanOffice = trim($this->officeName);
        $ticketsQuery = Ticket::with(['technicalDetail'])
            ->where(function ($q) use ($cleanOffice) {
                $q->where('office_name', $cleanOffice)
                    ->orWhere('office_name', 'like', "%{$cleanOffice}%");
            });

        if (! empty($this->historySearch)) {
            $term = trim($this->historySearch);
            $ticketsQuery->where(function ($q) use ($term) {
                $q->where('ticket_number', 'like', "%{$term}%")
                    ->orWhere('subject', 'like', "%{$term}%")
                    ->orWhere('applicant_name', 'like', "%{$term}%")
                    ->orWhere('category', 'like', "%{$term}%")
                    ->orWhere('status', 'like', "%{$term}%");
            });
        }

        $officeTickets = $ticketsQuery->latest()->take(20)->get();

        return view('livewire.helpdesk.new-ticket', [
            'offices' => $offices,
            'officeTickets' => $officeTickets,
        ]);
    }
}
