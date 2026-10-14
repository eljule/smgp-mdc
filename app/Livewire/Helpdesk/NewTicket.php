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

    // 1. Datos de Identificación y Oficina (Columna Izquierda)
    public string $officeName = 'OFICINA DE REGIDORES';

    public string $applicantName = 'JULIO ERNESTO CHAVEZ CRISANTO';

    public string $applicantPosition = 'ESPECIALISTA TECNOLÓGICO';

    public string $applicantPhone = 'Anexo 102';

    public string $applicantEmail = 'jchavez@castilla.gob.pe';

    // 2. Escaneo de Código QR o Activo Informático (Opcional)
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

    // 4. Seguimiento de Tickets del Área (Columna Derecha)
    public string $trackingStatusFilter = 'TODOS'; // 'TODOS' | 'ACTIVOS' | 'RESUELTOS' | 'CERRADOS'

    public string $trackingSearch = '';

    // 5. Modal de Visto Bueno (Conformidad de Cierre)
    public bool $isConformityModalOpen = false;

    public ?int $conformityTicketId = null;

    public ?Ticket $conformityTicket = null;

    public bool $formUserConformity = true;

    public int $formUserRating = 5;

    public string $formUserFeedback = 'Solución técnica verificada satisfactoriamente en mi puesto.';

    // Estados de envío y alertas
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
            $this->applicantName = $user->name ?? $user->username ?? 'JULIO ERNESTO CHAVEZ CRISANTO';
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

        // Buscar si existe un responsable asignado a esta dependencia
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
        $this->formUserFeedback = 'Solución técnica verificada satisfactoriamente en mi puesto.';
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
            $this->formUserFeedback = 'Solución técnica verificada satisfactoriamente en mi puesto.';
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
        $this->flashMessage = "Ticket {$ticket->ticket_number} registrado exitosamente. Ya es visible en el seguimiento del área.";

        // Limpiar campos del formulario manteniendo la oficina
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
        $baseQuery = Ticket::with(['technicalDetail', 'office'])
            ->where(function ($q) use ($cleanOffice) {
                $q->where('office_name', $cleanOffice)
                    ->orWhere('office_name', 'like', "%{$cleanOffice}%");
            });

        // Contadores para pestañas de seguimiento
        $allCount = (clone $baseQuery)->count();
        $activeCount = (clone $baseQuery)->whereIn('status', ['ABIERTO', 'EN_ATENCION', 'EN_LABORATORIO', 'EN_PAUSA'])->count();
        $resolvedCount = (clone $baseQuery)->where('status', 'RESUELTO')->count();
        $closedCount = (clone $baseQuery)->where('status', 'CERRADO')->count();

        // Aplicar filtro de estado
        if ($this->trackingStatusFilter === 'ACTIVOS') {
            $baseQuery->whereIn('status', ['ABIERTO', 'EN_ATENCION', 'EN_LABORATORIO', 'EN_PAUSA']);
        } elseif ($this->trackingStatusFilter === 'RESUELTOS') {
            $baseQuery->where('status', 'RESUELTO');
        } elseif ($this->trackingStatusFilter === 'CERRADOS') {
            $baseQuery->where('status', 'CERRADO');
        }

        // Búsqueda en seguimiento
        if (! empty($this->trackingSearch)) {
            $term = trim($this->trackingSearch);
            $baseQuery->where(function ($q) use ($term) {
                $q->where('ticket_number', 'like', "%{$term}%")
                    ->orWhere('subject', 'like', "%{$term}%")
                    ->orWhere('applicant_name', 'like', "%{$term}%")
                    ->orWhere('assigned_technician_name', 'like', "%{$term}%")
                    ->orWhere('category', 'like', "%{$term}%")
                    ->orWhere('status', 'like', "%{$term}%");
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
