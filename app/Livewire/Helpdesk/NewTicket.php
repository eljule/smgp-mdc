<?php

declare(strict_types=1);

namespace App\Livewire\Helpdesk;

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
    'title' => 'Registrar Falla en su Puesto de Trabajo - SIGM Castilla',
])]
#[Title('Registrar Falla en su Puesto de Trabajo - SIGM Castilla')]
class NewTicket extends Component
{
    use WithFileUploads;

    // 1. Datos de Identificación (RF-02) - Conservados exactamente iguales
    public string $applicantName = 'ADMINISTRADOR DEL SISTEMA SIGM';

    public string $officeName = 'OFICINA DE REGIDORES';

    public string $applicantPhone = 'Anexo 104';

    public string $applicantEmail = 'admin@castilla.gob.pe';

    // 2. Escaneo de Código QR (RF-03) - Conservado exactamente igual
    public string $qrCodeInput = '';

    public ?int $selectedAssetId = null;

    public ?string $selectedAssetCode = null;

    public ?string $selectedAssetCategory = null;

    public ?string $selectedAssetName = null;

    public ?string $assetLookupError = null;

    // 3. Formulario de Incidencia (RF-04) - Conservado exactamente igual
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

    public ?string $flashMessage = null;

    public string $flashType = 'success';

    // =========================================================================
    // SEGUIMIENTO DE TICKETS DEL ÁREA Y VISTO BUENO (Espacio Derecho Adicionado)
    // =========================================================================
    public string $trackingStatusFilter = 'TODOS'; // 'TODOS' | 'ACTIVOS' | 'RESUELTOS' | 'CERRADOS'

    public string $trackingSearch = '';

    public bool $isConformityModalOpen = false;

    public ?int $conformityTicketId = null;

    public ?Ticket $conformityTicket = null;

    public bool $formUserConformity = true;

    public int $formUserRating = 5;

    public string $formUserFeedback = 'Solución verificada satisfactoriamente en mi puesto de trabajo.';

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
        $this->flashMessage = "Ticket {$ticket->ticket_number} registrado exitosamente. Su reporte fue enviado a Soporte TI.";
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

    // =========================================================================
    // MÉTODOS DEL ESPACIO DE SEGUIMIENTO Y VISTO BUENO DE CIERRE
    // =========================================================================
    public function setTrackingFilter(string $filter): void
    {
        $this->trackingStatusFilter = $filter;
    }

    public function openConformityModal(int $ticketId): void
    {
        $this->conformityTicketId = $ticketId;
        $this->conformityTicket = Ticket::with(['technicalDetail', 'office'])->find($ticketId);
        $this->formUserConformity = true;
        $this->formUserRating = 5;
        $this->formUserFeedback = 'Solución verificada satisfactoriamente en mi puesto de trabajo.';
        $this->isConformityModalOpen = true;
    }

    public function closeConformityModal(): void
    {
        $this->isConformityModalOpen = false;
        $this->conformityTicketId = null;
        $this->conformityTicket = null;
    }

    public function setConformity(bool $val): void
    {
        $this->formUserConformity = $val;
        if (! $val) {
            $this->formUserFeedback = 'El inconveniente persiste o requiere revisión técnica adicional.';
        } else {
            $this->formUserFeedback = 'Solución verificada satisfactoriamente en mi puesto de trabajo.';
        }
    }

    public function setRating(int $val): void
    {
        $this->formUserRating = max(1, min(5, $val));
    }

    public function saveUserConformity(): void
    {
        if (! $this->conformityTicketId) {
            return;
        }

        $ticket = Ticket::find($this->conformityTicketId);
        if (! $ticket) {
            $this->closeConformityModal();

            return;
        }

        $ticket->user_conformity = $this->formUserConformity;
        $ticket->user_rating = $this->formUserRating;
        $ticket->user_feedback = $this->formUserFeedback;
        $ticket->status = $this->formUserConformity ? 'CERRADO' : 'EN_ATENCION';
        if ($this->formUserConformity) {
            $ticket->closed_at = now();
            if (! $ticket->resolved_at) {
                $ticket->resolved_at = now();
            }
        }
        $ticket->save();

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'previous_status' => $ticket->getOriginal('status'),
            'new_status' => $ticket->status,
            'action' => 'CONFORMIDAD_USUARIO',
            'performed_by_user_name' => $ticket->applicant_name ?: $this->applicantName,
            'notes' => $this->formUserConformity
                ? "Visto bueno otorgado por el usuario. Calificación: {$this->formUserRating}/5 estrellas. {$this->formUserFeedback}"
                : "Observación de disconformidad registrada: {$this->formUserFeedback}",
        ]);

        $this->isConformityModalOpen = false;
        $this->flashMessage = $this->formUserConformity
            ? "¡Visto Bueno otorgado exitosamente! El ticket {$ticket->ticket_number} fue cerrado formalmente."
            : "Observación registrada para el ticket {$ticket->ticket_number}. Soporte TI continuará con la atención.";
        $this->flashType = $this->formUserConformity ? 'success' : 'info';
    }

    public function render(): View
    {
        $offices = Office::orderBy('name')->get();

        // Consulta de tickets del área especificada en officeName
        $cleanOffice = trim($this->officeName);
        $baseQuery = Ticket::with(['technicalDetail', 'office'])
            ->when(! empty($cleanOffice), function ($q) use ($cleanOffice) {
                $q->where(function ($sub) use ($cleanOffice) {
                    $sub->where('office_name', $cleanOffice)
                        ->orWhere('office_name', 'like', "%{$cleanOffice}%");
                });
            });

        $allCount = (clone $baseQuery)->count();
        $activeCount = (clone $baseQuery)->whereIn('status', ['ABIERTO', 'EN_ATENCION', 'EN_LABORATORIO', 'EN_PAUSA'])->count();
        $resolvedCount = (clone $baseQuery)->where('status', 'RESUELTO')->count();
        $closedCount = (clone $baseQuery)->where('status', 'CERRADO')->count();

        if ($this->trackingStatusFilter === 'ACTIVOS') {
            $baseQuery->whereIn('status', ['ABIERTO', 'EN_ATENCION', 'EN_LABORATORIO', 'EN_PAUSA']);
        } elseif ($this->trackingStatusFilter === 'RESUELTOS') {
            $baseQuery->where('status', 'RESUELTO');
        } elseif ($this->trackingStatusFilter === 'CERRADOS') {
            $baseQuery->where('status', 'CERRADO');
        }

        if (! empty($this->trackingSearch)) {
            $term = trim($this->trackingSearch);
            $baseQuery->where(function ($q) use ($term) {
                $q->where('ticket_number', 'like', "%{$term}%")
                    ->orWhere('subject', 'like', "%{$term}%")
                    ->orWhere('applicant_name', 'like', "%{$term}%")
                    ->orWhere('assigned_technician_name', 'like', "%{$term}%");
            });
        }

        $officeTickets = $baseQuery->latest()->take(25)->get();

        return view('livewire.helpdesk.new-ticket', [
            'offices' => $offices,
            'officeTickets' => $officeTickets,
            'allCount' => $allCount,
            'activeCount' => $activeCount,
            'resolvedCount' => $resolvedCount,
            'closedCount' => $closedCount,
        ]);
    }
}
