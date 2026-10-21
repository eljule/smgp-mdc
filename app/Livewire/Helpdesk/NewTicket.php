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
use Illuminate\Support\Facades\DB;
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

    /**
     * Lista de dependencias municipales a su cargo (titular y por jerarquía).
     *
     * @var array<int, array{id: int, name: string, acronym: ?string, is_primary: bool, is_inherited: bool, badge: string}>
     */
    public array $assignedOffices = [];

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

    public ?string $flashMessage = null;

    public string $flashType = 'success';

    // =========================================================================
    // SEGUIMIENTO DE TICKETS DEL ÁREA Y VISTO BUENO (Espacio Derecho)
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
            $this->applicantName = $user->full_name ?? $user->name ?? $user->username ?? 'ADMINISTRADOR DEL SISTEMA SIGM';
            $this->applicantEmail = $user->email ?? 'admin@castilla.gob.pe';
        }

        $office = request()->query('oficina') ?? request()->query('office');
        $dni = request()->query('dni');

        if (! empty($dni)) {
            $dniStr = trim((string) $dni);
            $person = Person::where('document_number', $dniStr)->first();
            if ($person) {
                $this->applicantName = $person->full_name;
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

        $preferredOffice = ! empty($office) ? trim((string) $office) : null;
        $this->resolveOfficesForApplicant($this->applicantName, $preferredOffice);

        $assetCode = $asset ?? request()->query('asset');
        if (! empty($assetCode)) {
            $this->qrCodeInput = (string) $assetCode;
            $this->lookupAsset();
        }
    }

    public function updatedApplicantName(string $value): void
    {
        $this->resolveOfficesForApplicant($value);
    }

    /**
     * Busca una persona por documento, nombre exacto o palabras clave.
     */
    protected function findPersonByNameOrDoc(string $term): ?Person
    {
        $clean = trim($term);
        if ($clean === '') {
            return null;
        }

        // Búsqueda directa por documento
        $byDoc = Person::where('document_number', $clean)->first();
        if ($byDoc) {
            return $byDoc;
        }

        // Búsqueda en memoria por nombre completo exacto
        $allActive = Person::where('is_active', true)->get();
        $match = $allActive->first(function ($p) use ($clean) {
            return mb_strtoupper($p->full_name) === mb_strtoupper($clean);
        });

        if ($match) {
            return $match;
        }

        // Búsqueda por palabras clave relevantes
        $words = array_values(array_filter(explode(' ', $clean), fn ($w) => mb_strlen($w) >= 3));
        if (count($words) >= 2) {
            $like = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

            return Person::where('is_active', true)
                ->where(function ($q) use ($words, $like) {
                    foreach ($words as $w) {
                        $q->where(function ($sub) use ($w, $like) {
                            $sub->where('first_name', $like, "%{$w}%")
                                ->orWhere('paternal_surname', $like, "%{$w}%")
                                ->orWhere('maternal_surname', $like, "%{$w}%");
                        });
                    }
                })->first();
        }

        return null;
    }

    /**
     * Resuelve las dependencias municipales asignadas y encargadas por jerarquía para el funcionario/custodio.
     */
    public function resolveOfficesForApplicant(?string $name = null, ?string $preferredOffice = null): void
    {
        $applicant = trim((string) ($name ?? $this->applicantName));
        if (empty($applicant)) {
            $this->assignedOffices = [];

            return;
        }

        // 1. Buscar la persona en la base de datos
        $person = $this->findPersonByNameOrDoc($applicant);

        if (! $person) {
            $this->assignedOffices = [];
            if (! empty($preferredOffice)) {
                $this->officeName = $preferredOffice;
            }

            return;
        }

        // Normalizar nombre oficial del funcionario/custodio
        $this->applicantName = $person->full_name;

        // Actualizar datos de contacto institucionales
        if (! empty($person->phone)) {
            $this->applicantPhone = $person->phone;
        }
        if (! empty($person->email)) {
            $this->applicantEmail = $person->email;
        }

        $allOffices = Office::where('is_active', true)->orderBy('name')->get();
        $allPeople = Person::where('is_active', true)->get();

        // 2. Determinar la oficina principal de la persona
        $primaryOffice = null;
        if (! empty($person->office)) {
            if (preg_match('/\[(.*?)\]/', $person->office, $matches)) {
                $acronym = trim($matches[1]);
                $primaryOffice = $allOffices->firstWhere('acronym', $acronym);
            }
            if (! $primaryOffice) {
                $cleanOffice = trim(preg_replace('/\[.*?\]/', '', $person->office));
                $primaryOffice = $allOffices->first(fn ($o) => strtoupper(trim($o->name)) === strtoupper($cleanOffice))
                    ?? $allOffices->first(fn ($o) => ! empty($cleanOffice) && str_contains(strtoupper($o->name), strtoupper($cleanOffice)));
            }
        }

        $list = [];

        // Agregar oficina principal
        if ($primaryOffice) {
            $list[] = [
                'id' => $primaryOffice->id,
                'name' => $primaryOffice->name,
                'acronym' => $primaryOffice->acronym,
                'is_primary' => true,
                'is_inherited' => false,
                'badge' => 'Principal',
            ];
        }

        // 3. Oficinas que actualmente no tienen responsable y recaen en esta persona por jerarquía
        foreach ($allOffices as $off) {
            $resp = $off->resolveActiveResponsible($allPeople, $allOffices);
            $isMatch = ($resp['person']?->id === $person->id)
                || (strtoupper(trim($resp['name'])) === strtoupper(trim($person->full_name)));

            // Únicamente dependencias donde no hay responsable titular propio (is_inherited = true)
            if ($resp['is_inherited'] && $isMatch) {
                if (! collect($list)->contains('id', $off->id)) {
                    $list[] = [
                        'id' => $off->id,
                        'name' => $off->name,
                        'acronym' => $off->acronym,
                        'is_primary' => false,
                        'is_inherited' => true,
                        'badge' => 'Encargada por jerarquía (Sin titular)',
                    ];
                }
            }
        }

        $this->assignedOffices = $list;

        // 5. Preseleccionar la oficina: oficina preferida si vino en URL, o la principal por defecto
        if (! empty($preferredOffice)) {
            $this->officeName = $preferredOffice;
        } elseif ($primaryOffice) {
            $this->officeName = $primaryOffice->name;
        } elseif (! empty($list)) {
            $this->officeName = $list[0]['name'];
        }
    }

    public function autoDetect(): void
    {
        $user = auth()->user();
        if ($user) {
            $person = $user->person;
            if (! $person && ! empty($user->full_name)) {
                $person = $this->findPersonByNameOrDoc((string) $user->full_name);
            }

            if ($person) {
                $this->applicantName = $person->full_name;
                $this->applicantEmail = $person->email ?: ($user->email ?? 'admin@castilla.gob.pe');
                $this->applicantPhone = $person->phone ?: 'Anexo 104';
                $this->resolveOfficesForApplicant($person->full_name);

                return;
            }

            $this->applicantName = $user->full_name ?: $user->name;
            $this->applicantEmail = $user->email ?: 'admin@castilla.gob.pe';
            $this->applicantPhone = 'Anexo 104';
            $this->resolveOfficesForApplicant($this->applicantName);

            return;
        }

        $this->applicantName = 'ADMINISTRADOR DEL SISTEMA SIGM';
        $this->applicantPhone = 'Anexo 104';
        $this->applicantEmail = 'admin@castilla.gob.pe';
        $this->officeName = 'OFICINA DE REGIDORES';
        $this->assignedOffices = [];
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

            if ($asset->assignedPerson) {
                $this->applicantName = $asset->assignedPerson->full_name;
                $this->resolveOfficesForApplicant($this->applicantName, $asset->office);
            } elseif (! empty($asset->office)) {
                $this->officeName = $asset->office;
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

    public function canApproveConformity(?Ticket $ticket = null): bool
    {
        if (! auth()->check()) {
            return false;
        }
        if ($ticket) {
            return $ticket->canUserGiveConformity(auth()->user());
        }
        return auth()->user()->canApproveTicketConformity();
    }

    public function getPendingConformityTickets(): \Illuminate\Support\Collection
    {
        $user = auth()->user();
        if (! $user) {
            return collect();
        }

        return Ticket::with(['technicalDetail', 'office', 'assignedTechnician'])
            ->whereIn('status', ['RESUELTO', 'CERRADO'])
            ->where(function ($q) {
                $q->whereNull('user_conformity')->orWhere('user_conformity', false);
            })
            ->get()
            ->filter(fn ($t) => $t->isApplicantUser($user));
    }

    public function openConformityModal(int $ticketId): void
    {
        $ticket = Ticket::with(['technicalDetail', 'office', 'assignedTechnician'])->find($ticketId);
        if (! $ticket) {
            $this->errorMessage = 'Ticket no encontrado.';
            return;
        }

        if (! $ticket->canUserGiveConformity(auth()->user())) {
            if (! $ticket->canReceiveUserConformity()) {
                $this->flashMessage = $ticket->user_conformity
                    ? "El ticket {$ticket->ticket_number} ya cuenta con visto bueno registrado."
                    : "El visto bueno solo puede otorgarse a tickets resueltos o cerrados pendientes de conformidad.";
            } else {
                $this->flashMessage = "Acceso restringido: El visto bueno solo puede ser otorgado por el usuario solicitante, el Jefe de Oficina de ODT, el usuario root o personal de soporte técnico autorizado.";
            }
            $this->flashType = 'warning';
            return;
        }

        $this->conformityTicketId = $ticketId;
        $this->conformityTicket = $ticket;
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

        if (! $ticket->canUserGiveConformity(auth()->user())) {
            $this->closeConformityModal();
            $this->flashMessage = 'Acción no permitida: No cuenta con autorización para otorgar visto bueno a este ticket.';
            $this->flashType = 'warning';
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
        $allPeople = Person::where('is_active', true)->orderBy('first_name')->get();

        // Consulta de tickets del área especificada en officeName
        $cleanOffice = trim($this->officeName);
        $baseQuery = Ticket::with(['technicalDetail', 'office', 'assignedTechnician'])
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
            'allPeople' => $allPeople,
            'officeTickets' => $officeTickets,
            'allCount' => $allCount,
            'activeCount' => $activeCount,
            'resolvedCount' => $resolvedCount,
            'closedCount' => $closedCount,
        ]);
    }
}
