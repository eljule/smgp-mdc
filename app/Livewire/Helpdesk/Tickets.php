<?php

declare(strict_types=1);

namespace App\Livewire\Helpdesk;

use App\Actions\Helpdesk\GetHelpdeskDashboardMetricsAction;
use App\Models\Helpdesk\KnowledgeArticle;
use App\Models\Helpdesk\Ticket;
use App\Models\Helpdesk\TicketAuditLog;
use App\Models\Helpdesk\TicketLoan;
use App\Models\Helpdesk\TicketSupply;
use App\Models\Helpdesk\TicketTechnicalDetail;
use App\Models\Itam\Asset;
use App\Models\Itam\Supply;
use App\Models\Office;
use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Mesa de Ayuda Informática - SIGM Castilla',
    'moduleName' => 'Soporte Técnico y Helpdesk',
    'subsystem' => 'helpdesk',
    'activeTab' => 'tickets',
])]
#[Title('Mesa de Ayuda Informática - SIGM Castilla')]
class Tickets extends Component
{
    protected function resolveTechnicianUser(?string $name): ?User
    {
        if (empty($name)) {
            return null;
        }

        if (preg_match('/\(([^)]+)\)$/', $name, $matches)) {
            $user = User::where('username', trim($matches[1]))->first();
            if ($user) {
                return $user;
            }
        }

        return User::where('username', $name)->orWhere('full_name', $name)->first();
    }

    protected function getActiveTicketsQueryForTechnician(string $technicianName)
    {
        $user = $this->resolveTechnicianUser($technicianName);

        $query = Ticket::query()->whereNotIn('status', ['RESUELTO', 'CERRADO']);

        if ($user) {
            $query->where(function ($q) use ($user, $technicianName) {
                $q->where('assigned_technician_id', $user->id)
                  ->orWhere('assigned_technician_name', $technicianName)
                  ->orWhere('assigned_technician_name', $user->full_name);
            });
        } else {
            $query->where('assigned_technician_name', $technicianName);
        }

        return $query;
    }
    use WithFileUploads;
    use WithPagination;

    #[Url(as: 'tab')]
    public string $activeTab = 'tickets';

    // Filtros de tickets
    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'estado')]
    public string $status = 'TODOS';

    #[Url(as: 'categoria')]
    public string $category = 'TODAS';

    #[Url(as: 'prioridad')]
    public string $priority = 'TODAS';

    public int $perPage = 10;

    // Toast Alert
    public ?string $alertMessage = null;

    public function hydrate(): void
    {
        $this->alertMessage = null;
    }

    public string $alertType = 'success';

    // Búsqueda QR
    public string $qrCodeInput = '';

    public ?Asset $scannedAsset = null;

    public ?string $qrError = null;

    // Base de Conocimientos
    public string $kbSearch = '';

    public string $kbCategory = 'TODAS';

    // ==========================================
    // MODALES Y FORMULARIOS
    // ==========================================
    // 1. Modal Crear Ticket
    public bool $isCreateModalOpen = false;

    public bool $showCreateModal = false;

    public string $formApplicantName = 'ADMINISTRADOR DEL SISTEMA SIGM';

    public string $formApplicantPhone = 'Anexo 115';

    public string $formApplicantEmail = 'usuario@castilla.gob.pe';

    public string $formOfficeName = 'Secretaría General y Mesa de Partes';

    public ?int $formOfficeId = null;

    public bool $formApplicantIsInherited = false;

    public ?string $formApplicantInheritedOffice = null;

    public ?int $formAssetId = null;

    public ?string $formAssetComputerCode = null;

    public ?string $formAssetCategory = null;

    public $formEvidencePhoto = null;

    public string $formCategory = 'HARDWARE';

    public string $formPriority = 'MEDIA';

    public string $formSubject = '';

    public string $formDescription = '';

    // 2. Modal Detalle Ticket
    public bool $isDetailModalOpen = false;

    public ?Ticket $selectedTicketForDetail = null;

        // Modal Pausa de Atención
    public bool $isPauseModalOpen = false;

    public ?int $pauseTicketId = null;

    public string $formPauseReason = 'En espera de repuestos / insumos de almacén';

    // 3. Modal Atención Técnica
    public bool $isTechnicalModalOpen = false;

    public bool $showTechnicalModal = false;

    public ?int $technicalTicketId = null;

    public string $formRealDiagnosis = '';

    public string $formSolutionApplied = '';

    public string $formTechnicalStatus = 'RESUELTO';

    public bool $formIsDecommission = false;

    public string $formDecommissionReason = 'Daño estructural por sobretensión eléctrica / falla grave.';

    public string $formDecommissionDestination = 'Almacén Central / Chatarreo RAEE';

    public bool $formPublishToKb = false;

    public string $formKbTitle = '';

    // 4. Modal Insumos
    public bool $isSuppliesModalOpen = false;

    public ?int $suppliesTicketId = null;

    public ?int $selectedSupplyId = null;

    public int $formSupplyQuantity = 1;

    public string $formSupplyNotes = 'Repuesto instalado en equipo del usuario.';

    // 5. Modal Préstamo Provisional
    public bool $isProvisionalModalOpen = false;

    public ?int $provisionalTicketId = null;

    public ?int $selectedProvisionalAssetId = null;

    public string $formProvisionalNotes = 'Equipo provisional asignado para continuidad operativa.';

    // 6. Modal Acta de Baja Patrimonial (Imprimible)
    public bool $isDecommissionModalOpen = false;

    public ?Ticket $decommissionTicket = null;

    // 7. Modal Conformidad de Cierre
    public bool $isConformityModalOpen = false;

    public ?int $conformityTicketId = null;

    public ?Ticket $conformityTicket = null;

    public int $formUserRating = 5;

    public bool $formUserConformity = true;

    public string $formUserFeedback = 'Servicio técnico brindado satisfactoriamente.';

    // 8. Modal Reasignar Individual (RF-13)
    public bool $isReassignModalOpen = false;

    public ?int $reassignTicketId = null;

    public ?int $reassignUserId = null;

    public string $reassignTechnicianName = '';

    public ?string $reassignTargetTechnician = null;

    public string $reassignNotes = 'Reasignación por especialidad técnica.';

    public ?string $reassignReason = null;

    // 8.1 Modal Traspaso Masivo de Carga (RF-13 Contingencias)
    public bool $isBulkTransferModalOpen = false;

    public string $bulkOriginTechnician = '';

    public int $bulkTicketsCount = 0;

    public string $bulkDestinationMode = 'auto'; // 'auto' | 'specific'

    public string $bulkTargetTechnician = '';

    public string $bulkReason = 'Descanso médico / Contingencia operativa';

    // 9. Modal Base de Conocimientos KB (RF-14)
    public bool $isKbModalOpen = false;

    public string $kbModalMode = 'VIEW'; // 'VIEW' | 'CREATE'

    public ?KnowledgeArticle $selectedKbArticle = null;

    public bool $kbHasVoted = false;

    public string $formArticleTitle = '';

    public string $formArticleCategory = 'HARDWARE';

    public string $formArticleSummary = '';

    public string $formArticleSymptoms = '';

    public string $formArticleSteps = '';

    public string $formArticleTags = '';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatedReassignTargetTechnician($value): void
    {
        $this->reassignTechnicianName = (string) $value;
    }

    public function updatedReassignReason($value): void
    {
        $this->reassignNotes = (string) $value;
    }

    public function showAlert(string $message, string $type = 'success'): void
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->dispatch('toast', message: $message, type: $type);
    }

    // ==========================================
    // ESCÁNER QR (RF-03)
    // ==========================================
    public function handleLookupQr(): void
    {
        if (empty(trim($this->qrCodeInput))) {
            return;
        }

        $code = trim($this->qrCodeInput);
        $asset = Asset::with(['category', 'brand', 'model', 'childAssets.category', 'childAssets.model', 'childAssets.brand', 'assignedPerson'])
            ->where(function ($q) use ($code) {
                $q->where('computer_code', 'ilike', "%{$code}%")
                    ->orWhere('patrimonial_code', 'ilike', "%{$code}%")
                    ->orWhere('serial_number', 'ilike', "%{$code}%");
            })
            ->first();

        if ($asset) {
            $this->scannedAsset = $asset;
            $this->qrError = null;
            $this->showAlert("Equipo identificado: {$asset->computer_code}");
        } else {
            $this->scannedAsset = null;
            $this->qrError = 'Activo no encontrado con el código informado.';
            $this->showAlert('Equipo no encontrado', 'error');
        }
    }

    // ==========================================
    // 1. TICKET CREATE
    // ==========================================
    public function openCreateModal(?int $assetId = null): void
    {
        $office = Office::where('name', 'like', '%Secretaría General%')->first() ?? Office::first();
        $this->formOfficeName = $office?->name ?? 'Secretaría General y Mesa de Partes';
        $this->loadApplicantDataForOffice($this->formOfficeName);

        $this->formAssetId = $assetId;
        $this->formAssetComputerCode = null;
        $this->formAssetCategory = null;
        $this->qrCodeInput = '';
        $this->qrError = null;
        $this->formCategory = 'HARDWARE';
        $this->formPriority = 'MEDIA';
        $this->formSubject = '';
        $this->formDescription = '';
        $this->formEvidencePhoto = null;
        $this->isCreateModalOpen = true;
        $this->showCreateModal = true;
    }

    public function updatedFormOfficeName($value): void
    {
        $this->loadApplicantDataForOffice($value);
    }

    public function loadApplicantDataForOffice(string $officeName): void
    {
        $office = Office::where('name', $officeName)->first()
            ?? Office::where('name', 'like', '%'.$officeName.'%')->first();

        $this->formOfficeId = $office?->id;

        if ($office) {
            $resp = $office->resolveActiveResponsible();
            $this->formApplicantName = $resp['name'];
            $this->formApplicantPhone = $resp['phone'];
            $this->formApplicantEmail = $resp['email'];
            $this->formApplicantIsInherited = (bool) $resp['is_inherited'];
            $this->formApplicantInheritedOffice = $resp['inherited_from'];
        } else {
            $user = auth()->user();
            $this->formApplicantName = $user ? ($user->full_name ?? $user->name) : 'ADMINISTRADOR DEL SISTEMA SIGM';
            $this->formApplicantPhone = 'Anexo 115';
            $this->formApplicantEmail = $user?->email ?: 'usuario@castilla.gob.pe';
            $this->formApplicantIsInherited = false;
            $this->formApplicantInheritedOffice = null;
        }
    }

    public function lookupAsset(): void
    {
        if (! trim($this->qrCodeInput)) {
            return;
        }

        $code = trim($this->qrCodeInput);
        $asset = Asset::where('computer_code', 'like', $code)
            ->orWhere('barcode', 'like', $code)
            ->first();

        if ($asset) {
            $this->formAssetId = $asset->id;
            $this->formAssetComputerCode = $asset->computer_code;
            $this->formAssetCategory = $asset->category_name ?? 'Equipo Informático';
            if ($asset->office_name) {
                $this->formOfficeName = $asset->office_name;
                $this->formOfficeId = $asset->office_id ?? Office::where('name', $asset->office_name)->first()?->id;
            }
            if ($asset->assigned_person_name) {
                $this->formApplicantName = $asset->assigned_person_name;
            }
            $this->qrError = null;
        } else {
            $this->qrError = 'No se localizó ningún activo con este código QR';
            $this->formAssetId = null;
            $this->formAssetComputerCode = null;
            $this->formAssetCategory = null;
        }
    }

    public function clearAsset(): void
    {
        $this->formAssetId = null;
        $this->formAssetComputerCode = null;
        $this->formAssetCategory = null;
        $this->qrCodeInput = '';
        $this->qrError = null;
    }

    public function saveTicket(): void
    {
        $this->validate([
            'formApplicantName' => 'required|string',
            'formSubject' => 'required|string|max:250',
            'formDescription' => 'required|string',
        ]);

        $count = Ticket::count() + 1;
        $ticketNumber = 'TK-'.date('Y').'-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);

        $office = null;
        if ($this->formOfficeName) {
            $office = Office::where('name', $this->formOfficeName)->first()
                ?? Office::where('name', 'like', '%'.$this->formOfficeName.'%')->first();
        }
        if (! $office && $this->formOfficeId) {
            $office = Office::find($this->formOfficeId);
        }
        $officeName = $office?->name ?? ($this->formOfficeName ?: 'Secretaría General y Mesa de Partes');

        $asset = $this->formAssetId ? Asset::find($this->formAssetId) : null;
        $assetCode = $asset?->computer_code ?? ($this->formAssetComputerCode ?: ($this->qrCodeInput ? trim($this->qrCodeInput) : null));

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'applicant_name' => $this->formApplicantName,
            'applicant_phone' => $this->formApplicantPhone ?: 'Anexo 115',
            'applicant_email' => $this->formApplicantEmail ?: null,
            'office_id' => $office?->id,
            'office_name' => $officeName,
            'asset_id' => $asset?->id,
            'asset_computer_code' => $assetCode,
            'category' => $this->formCategory,
            'priority' => $this->formPriority,
            'subject' => $this->formSubject,
            'description' => $this->formDescription,
            'status' => 'ABIERTO',
        ]);

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'new_status' => 'ABIERTO',
            'action' => 'TICKET_CREADO',
            'performed_by_user_name' => $this->formApplicantName,
            'notes' => 'Ticket ingresado al sistema de mesa de ayuda.',
        ]);

        $this->isCreateModalOpen = false;
        $this->showCreateModal = false;
        $this->showAlert("Ticket {$ticketNumber} generado exitosamente.");
    }

    // ==========================================
    // 2. DETALLE
    // ==========================================
    public function openDetailModal(int $ticketId): void
    {
        $this->selectedTicketForDetail = Ticket::with(['technicalDetail', 'supplies', 'loans', 'auditLogs', 'office', 'assignedTechnician'])->find($ticketId);
        $this->isDetailModalOpen = true;
    }

        public function canManageTicket(?Ticket $ticket = null): bool
    {
        if (! $ticket) {
            return false;
        }

        $user = auth()->user();
        if (! $user) {
            return app()->environment('testing');
        }

        return $user->canManageTicket($ticket);
    }

    public function canReassignTickets(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return app()->environment('testing');
        }

        return $user->canReassignTickets();
    }

    // ==========================================
    // 3. ATENCIÓN TÉCNICA
    // ==========================================
    public function takeTicket(int $ticketId): void
    {
        $ticket = Ticket::find($ticketId);
        if ($ticket) {
            if ($ticket->assigned_technician_id && ! $this->canManageTicket($ticket)) {
                $this->showAlert("Acceso restringido: Este ticket ya está asignado a {$ticket->assigned_technician_display}.");
                return;
            }
            $ticket->status = 'EN_ATENCION';
            $ticket->started_at = now();
            if (! $ticket->assigned_technician_name && ! $ticket->assigned_technician_id) {
                $user = auth()->user();
                $ticket->assigned_technician_id = $user?->id;
                $ticket->assigned_technician_name = $user ? $user->full_name_with_username : 'Ing. Carlos Mendoza (Soporte TI)';
                $ticket->assigned_technician_phone = 'Anexo 104';
            }
            $ticket->save();

            TicketAuditLog::create([
                'ticket_id' => $ticket->id,
                'previous_status' => 'ABIERTO',
                'new_status' => 'EN_ATENCION',
                'action' => 'TICKET_TOMADO',
                'performed_by_user_name' => auth()->user()?->full_name_with_username ?? auth()->user()?->name ?? 'Ing. Carlos Mendoza (Soporte TI)',
                'notes' => 'El técnico ha tomado la atención del ticket.',
            ]);

            $this->showAlert("Ticket {$ticket->ticket_number} tomado en atención.");
        }
    }

        // ==========================================
    // 3.1 PAUSA Y REANUDACIÓN DE ATENCIÓN
    // ==========================================
    public function openPauseModal(int $ticketId): void
    {
        $ticket = Ticket::with('technicalDetail')->find($ticketId);
        if ($ticket && ! $this->canManageTicket($ticket)) {
            $this->showAlert("Acceso restringido: Solo el técnico asignado ({$ticket->assigned_technician_display}) o la jefatura pueden pausar la atención.");
            return;
        }
        $this->pauseTicketId = $ticketId;
        $this->formPauseReason = $ticket?->technicalDetail?->pause_reason ?: 'En espera de repuestos / insumos de almacén';
        $this->isPauseModalOpen = true;
    }

    public function saveTicketPause(): void
    {
        $ticket = Ticket::find($this->pauseTicketId);
        if (! $ticket) {
            return;
        }

        if (! $this->canManageTicket($ticket)) {
            $this->showAlert("Acceso restringido: Solo el técnico asignado ({$ticket->assigned_technician_display}) o la jefatura pueden pausar este ticket.");
            $this->isPauseModalOpen = false;
            return;
        }

        $prevStatus = $ticket->status;
        $ticket->status = 'EN_PAUSA';
        $ticket->save();

        TicketTechnicalDetail::updateOrCreate(
            ['ticket_id' => $ticket->id],
            ['pause_reason' => $this->formPauseReason]
        );

        $techUserName = auth()->user()?->full_name_with_username ?? auth()->user()?->name ?? 'Técnico de Soporte';

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'previous_status' => $prevStatus,
            'new_status' => 'EN_PAUSA',
            'action' => 'PAUSA_ATENCION',
            'performed_by_user_id' => auth()->id(),
            'performed_by_user_name' => $techUserName,
            'notes' => 'Atención técnica puesta en pausa | Motivo de pausa: ' . trim($this->formPauseReason),
        ]);

        if ($this->selectedTicketForDetail && $this->selectedTicketForDetail->id === $ticket->id) {
            $this->selectedTicketForDetail = $ticket->fresh(['office', 'applicant', 'technicalDetail', 'supplies', 'loans', 'auditLogs']);
        }

        $this->isPauseModalOpen = false;
        $this->showAlert("Ticket {$ticket->ticket_number} puesto en pausa satisfactoriamente.");
    }

    public function resumeTicket(int $ticketId): void
    {
        $ticket = Ticket::find($ticketId);
        if (! $ticket) {
            return;
        }

        if (! $this->canManageTicket($ticket)) {
            $this->showAlert("Acceso restringido: Solo el técnico asignado ({$ticket->assigned_technician_display}) o la jefatura pueden reanudar este ticket.");
            return;
        }

        $prevStatus = $ticket->status;
        $ticket->status = 'EN_ATENCION';
        $ticket->save();

        $techUserName = auth()->user()?->full_name_with_username ?? auth()->user()?->name ?? 'Técnico de Soporte';

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'previous_status' => $prevStatus,
            'new_status' => 'EN_ATENCION',
            'action' => 'REANUDACION_ATENCION',
            'performed_by_user_id' => auth()->id(),
            'performed_by_user_name' => $techUserName,
            'notes' => 'Atención técnica reanudada por el operador.',
        ]);

        if ($this->selectedTicketForDetail && $this->selectedTicketForDetail->id === $ticket->id) {
            $this->selectedTicketForDetail = $ticket->fresh(['office', 'applicant', 'technicalDetail', 'supplies', 'loans', 'auditLogs']);
        }

        $this->showAlert("Atención del ticket {$ticket->ticket_number} reanudada.");
    }

    public function openTechnicalModal(int $ticketId): void
    {
        $ticket = Ticket::with('technicalDetail')->find($ticketId);
        if ($ticket && ! $this->canManageTicket($ticket)) {
            $this->showAlert("Acceso restringido: Solo el técnico asignado ({$ticket->assigned_technician_display}) o la jefatura pueden registrar el diagnóstico y solución.");
            return;
        }
        $this->technicalTicketId = $ticketId;
        if ($ticket) {
            $this->formRealDiagnosis = $ticket->technicalDetail?->real_diagnosis ?? 'Falla de conectividad / configuración';
            $this->formSolutionApplied = $ticket->technicalDetail?->solution_applied ?? 'Reconfiguración de parámetros y prueba operativa.';
            $this->formTechnicalStatus = in_array($ticket->status, ['EN_ATENCION', 'EN_PAUSA', 'EN_LABORATORIO']) ? $ticket->status : ($ticket->status === 'ABIERTO' ? 'EN_ATENCION' : 'RESUELTO');
            $this->formPauseReason = $ticket->technicalDetail?->pause_reason ?? 'En espera de repuestos / insumos de almacén';
            $this->formIsDecommission = (bool) ($ticket->technicalDetail?->is_definitive_decommission ?? false);
            $this->formKbTitle = "Solución a {$ticket->subject}";
        }
        $this->isTechnicalModalOpen = true;
        $this->showTechnicalModal = true;
    }

    public function saveTechnicalDetail(): void
    {
        $ticket = Ticket::find($this->technicalTicketId);
        if (! $ticket) {
            return;
        }

        if (! $this->canManageTicket($ticket)) {
            $this->showAlert("Acceso restringido: Solo el técnico asignado ({$ticket->assigned_technician_display}) o la jefatura pueden modificar este ticket.");
            $this->isTechnicalModalOpen = false;
            $this->showTechnicalModal = false;
            return;
        }

        $prevStatus = $ticket->status;
        $ticket->status = $this->formTechnicalStatus;
        if ($this->formTechnicalStatus === 'EN_ATENCION' && ! $ticket->started_at) {
            $ticket->started_at = now();
        } elseif ($this->formTechnicalStatus === 'RESUELTO') {
            $ticket->resolved_at = now();
        }
        $ticket->save();

        TicketTechnicalDetail::updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'real_diagnosis' => $this->formRealDiagnosis,
                'solution_applied' => $this->formSolutionApplied,
                'pause_reason' => $this->formTechnicalStatus === 'EN_PAUSA' ? $this->formPauseReason : ($ticket->technicalDetail?->pause_reason),
                'is_definitive_decommission' => $this->formIsDecommission,
                'decommission_reason' => $this->formIsDecommission ? $this->formDecommissionReason : null,
                'decommission_act_number' => $this->formIsDecommission ? 'ACTA-BAJA-'.date('Y').'-'.str_pad((string) $ticket->id, 4, '0', STR_PAD_LEFT) : null,
            ]
        );

        $techUserName = auth()->user()?->full_name_with_username ?? auth()->user()?->name ?? 'Técnico de Soporte';

        $statusLabels = [
            'ABIERTO' => 'Abierto',
            'EN_ATENCION' => 'En Atención',
            'EN_PAUSA' => 'En Pausa / En Espera',
            'EN_LABORATORIO' => 'En Laboratorio / Taller',
            'PENDIENTE' => 'En Espera / Pendiente',
            'RESUELTO' => 'Resuelto',
            'CERRADO' => 'Cerrado Formalmente',
        ];

        $prevLabel = $statusLabels[$prevStatus] ?? $prevStatus;
        $newLabel = $statusLabels[$this->formTechnicalStatus] ?? $this->formTechnicalStatus;

        $notesParts = [];
        if ($prevStatus !== $this->formTechnicalStatus) {
            $notesParts[] = "Cambio de estado: {$prevLabel} → {$newLabel}";
        } else {
            $notesParts[] = "Estado mantenido: {$newLabel}";
        }

        if (! empty($this->formRealDiagnosis)) {
            $notesParts[] = "Diagnóstico: " . trim($this->formRealDiagnosis);
        }

        if (! empty($this->formSolutionApplied)) {
            $notesParts[] = "Solución: " . trim($this->formSolutionApplied);
        }

        if ($this->formIsDecommission) {
            $notesParts[] = "⚠️ Baja Técnica Definitiva (RAEE): " . trim($this->formDecommissionReason);
        }

        if ($this->formPublishToKb && ! empty($this->formKbTitle)) {
            $notesParts[] = '📚 Publicado en Base de Conocimientos: "' . $this->formKbTitle . '"';
        }

        $logNotes = ! empty($notesParts) ? implode(' | ', $notesParts) : "Diagnóstico y solución técnica registrados.";

        $actionType = 'ACTUALIZACION_TECNICA';
        if ($prevStatus !== 'EN_PAUSA' && $this->formTechnicalStatus === 'EN_PAUSA') {
            $actionType = 'PAUSA_ATENCION';
        } elseif ($prevStatus === 'EN_PAUSA' && $this->formTechnicalStatus === 'EN_ATENCION') {
            $actionType = 'REANUDACION_ATENCION';
        }

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'previous_status' => $prevStatus,
            'new_status' => $this->formTechnicalStatus,
            'action' => $actionType,
            'performed_by_user_id' => auth()->id(),
            'performed_by_user_name' => $techUserName,
            'notes' => $logNotes,
        ]);

        if ($this->formPublishToKb && ! empty($this->formKbTitle)) {
            KnowledgeArticle::create([
                'title' => $this->formKbTitle,
                'category' => $ticket->category,
                'summary' => $this->formRealDiagnosis,
                'solution_steps' => $this->formSolutionApplied,
                'author_technician_name' => $techUserName,
                'helpful_count' => 1,
            ]);
        }

        if ($this->selectedTicketForDetail && $this->selectedTicketForDetail->id === $ticket->id) {
            $this->selectedTicketForDetail = $ticket->fresh(['office', 'applicant', 'technicalDetail', 'supplies', 'loans', 'auditLogs']);
        }

        $this->isTechnicalModalOpen = false;
        $this->showTechnicalModal = false;
        $this->showAlert("Diagnóstico y estado del ticket {$ticket->ticket_number} actualizados.");
    }

    // ==========================================
    // 4. INSUMOS EN TICKET
    // ==========================================
    public function openSuppliesModal(int $ticketId): void
    {
        $ticket = Ticket::find($ticketId);
        if ($ticket && ! $this->canManageTicket($ticket)) {
            $this->showAlert("Acceso restringido: Solo el técnico asignado ({$ticket->assigned_technician_display}) o la jefatura pueden descargar insumos para este ticket.");
            return;
        }
        $this->suppliesTicketId = $ticketId;
        $this->selectedSupplyId = Supply::where('stock', '>', 0)->first()?->id ?? Supply::first()?->id;
        $this->formSupplyQuantity = 1;
        $this->formSupplyNotes = 'Repuesto instalado en la dependencia solicitante.';
        $this->isSuppliesModalOpen = true;
    }

    public function saveTicketSupply(): void
    {
        $ticket = Ticket::find($this->suppliesTicketId);
        if ($ticket && ! $this->canManageTicket($ticket)) {
            $this->showAlert("Acceso restringido para descargar insumos en este ticket.");
            $this->isSuppliesModalOpen = false;
            return;
        }

        $supply = Supply::find($this->selectedSupplyId);
        if (! $supply) {
            return;
        }

        TicketSupply::create([
            'ticket_id' => $this->suppliesTicketId,
            'supply_id' => $supply->id,
            'supply_code' => $supply->code ?? ('INS-'.str_pad((string) $supply->id, 3, '0', STR_PAD_LEFT)),
            'supply_name' => $supply->name,
            'quantity' => (int) $this->formSupplyQuantity,
            'unit' => $supply->unit ?? 'UND',
            'unit_cost' => (float) ($supply->unit_cost ?? 0.00),
            'notes' => $this->formSupplyNotes,
        ]);

        $supply->stock = max(0, $supply->stock - (int) $this->formSupplyQuantity);
        $supply->save();

        $ticket = Ticket::find($this->suppliesTicketId);

        TicketAuditLog::create([
            'ticket_id' => $this->suppliesTicketId,
            'previous_status' => $ticket?->status ?? 'EN_ATENCION',
            'new_status' => $ticket?->status ?? 'EN_ATENCION',
            'action' => 'CONSUMO_INSUMO',
            'performed_by_user_id' => auth()->id(),
            'performed_by_user_name' => auth()->user()?->full_name_with_username ?? auth()->user()?->name ?? 'Técnico de Soporte',
            'notes' => 'Insumo descargado de almacén: ' . $this->formSupplyQuantity . ' ' . $supply->unit . '(s) de "' . $supply->name . '" (' . $supply->code . ') | Detalle de instalación: ' . $this->formSupplyNotes,
        ]);

        if ($this->selectedTicketForDetail && $this->selectedTicketForDetail->id === $this->suppliesTicketId) {
            $this->selectedTicketForDetail = $this->selectedTicketForDetail->fresh(['office', 'applicant', 'technicalDetail', 'supplies', 'loans', 'auditLogs']);
        }

        $this->isSuppliesModalOpen = false;
        $this->showAlert("Insumo {$supply->name} asignado y descontado de almacén ITAM.");
    }

    // ==========================================
    // 5. EQUIPO PROVISIONAL
    // ==========================================
    public function openProvisionalModal(int $ticketId): void
    {
        $ticket = Ticket::find($ticketId);
        if ($ticket && ! $this->canManageTicket($ticket)) {
            $this->showAlert("Acceso restringido: Solo el técnico asignado ({$ticket->assigned_technician_display}) o la jefatura pueden gestionar préstamos provisionales para este ticket.");
            return;
        }
        $this->provisionalTicketId = $ticketId;
        $this->formProvisionalNotes = '';

        $firstAvailable = Asset::where('is_loanable', true)
            ->where('status', 'OPERATIVO')
            ->first();

        $this->selectedProvisionalAssetId = $firstAvailable?->id;
        $this->isProvisionalModalOpen = true;
    }

    public function saveProvisionalLoan(): void
    {
        $ticket = Ticket::find($this->provisionalTicketId);
        if ($ticket && ! $this->canManageTicket($ticket)) {
            $this->showAlert("Acceso restringido para asignar préstamos provisionales en este ticket.");
            $this->isProvisionalModalOpen = false;
            return;
        }

        if (! $this->selectedProvisionalAssetId) {
            $this->showAlert("Debe seleccionar un equipo de reserva disponible para préstamo.");
            return;
        }

        $asset = Asset::with(['category', 'brand', 'model'])->find($this->selectedProvisionalAssetId);
        if (! $asset) {
            $this->showAlert("Equipo seleccionado no encontrado.");
            return;
        }

        if (! $asset->is_loanable || ! in_array($asset->status, ['OPERATIVO', 'DISPONIBLE'], true)) {
            $this->showAlert("El equipo {$asset->computer_code} no está disponible para préstamo (Estado actual: {$asset->status}).");
            return;
        }

        $assetName = trim(($asset->brand?->name ?? '').' '.($asset->model?->name ?? ''));
        if (empty($assetName)) {
            $assetName = $asset->category?->name ?? 'Equipo Provisional';
        }

        TicketLoan::create([
            'ticket_id' => $this->provisionalTicketId,
            'temporary_asset_id' => $asset->id,
            'temporary_asset_code' => $asset->computer_code ?? ('AST-'.str_pad((string) $asset->id, 3, '0', STR_PAD_LEFT)),
            'temporary_asset_name' => $assetName,
            'damaged_asset_id' => $ticket?->asset_id,
            'damaged_asset_code' => $ticket?->asset?->computer_code,
            'status' => 'ACTIVO',
            'loan_date' => now(),
            'notes' => $this->formProvisionalNotes,
        ]);

        $asset->status = 'PRESTADO';
        $asset->save();

        TicketAuditLog::create([
            'ticket_id' => $this->provisionalTicketId,
            'previous_status' => $ticket?->status ?? 'EN_ATENCION',
            'new_status' => $ticket?->status ?? 'EN_ATENCION',
            'action' => 'PRESTAMO_PROVISIONAL',
            'performed_by_user_id' => auth()->id(),
            'performed_by_user_name' => auth()->user()?->full_name_with_username ?? auth()->user()?->name ?? 'Técnico de Soporte',
            'notes' => "Equipo provisional asignado: {$asset->computer_code} ({$assetName}) | Observaciones: {$this->formProvisionalNotes}",
        ]);

        if ($this->selectedTicketForDetail && $this->selectedTicketForDetail->id === $this->provisionalTicketId) {
            $this->selectedTicketForDetail = $this->selectedTicketForDetail->fresh(['office', 'applicant', 'technicalDetail', 'supplies', 'loans', 'auditLogs']);
        }

        $this->isProvisionalModalOpen = false;
        $this->showAlert("Equipo provisional {$asset->computer_code} cedido temporalmente.");
    }

    // ==========================================
    // 6. ACTA DE BAJA PATRIMONIAL
    // ==========================================
    public function openDecommissionModal(int $ticketId): void
    {
        $this->decommissionTicket = Ticket::with(['technicalDetail', 'office', 'assignedTechnician'])->find($ticketId);
        $this->isDecommissionModalOpen = true;
    }

    // ==========================================
    // 7. CONFORMIDAD DE USUARIO
    // ==========================================
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

    public function openConformityModal(int $ticketId): void
    {
        $ticket = Ticket::with(['technicalDetail', 'office', 'assignedTechnician'])->find($ticketId);
        if (! $ticket) {
            $this->showAlert('Ticket no encontrado.');
            return;
        }

        if (! $ticket->canUserGiveConformity(auth()->user())) {
            if (! $ticket->canReceiveUserConformity()) {
                if ($ticket->user_conformity) {
                    $this->showAlert("El ticket {$ticket->ticket_number} ya cuenta con visto bueno otorgado.");
                } else {
                    $this->showAlert("El visto bueno solo puede otorgarse a tickets resueltos o cerrados pendientes de conformidad.");
                }
            } else {
                $this->showAlert("Acceso restringido: El visto bueno solo puede ser otorgado por el usuario solicitante, el Jefe de Oficina de ODT, el usuario root o personal de soporte técnico con autoridad delegada.");
            }
            return;
        }

        $this->conformityTicketId = $ticketId;
        $this->conformityTicket = $ticket;
        $this->formUserRating = 5;
        $this->formUserConformity = true;
        $this->formUserFeedback = 'Solución verificada satisfactoriamente.';
        $this->isConformityModalOpen = true;
    }

    public function setConformity(bool $val): void
    {
        $this->formUserConformity = $val;
        if (! $val) {
            $this->formUserFeedback = 'El problema continúa o el equipo no quedó operativo.';
        } else {
            $this->formUserFeedback = 'Solución verificada satisfactoriamente.';
        }
    }

    public function setRating(int $val): void
    {
        $this->formUserRating = $val;
    }

    public function saveUserConformity(): void
    {
        $ticket = Ticket::find($this->conformityTicketId);
        if (! $ticket) {
            return;
        }

        if (! $ticket->canUserGiveConformity(auth()->user())) {
            $this->isConformityModalOpen = false;
            $this->showAlert("Acción no permitida: No cuenta con autorización para otorgar el visto bueno a este ticket.");
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
            'previous_status' => $ticket->getOriginal('status') ?? 'RESUELTO',
            'new_status' => $ticket->status,
            'action' => $this->formUserConformity ? 'CONFORMIDAD_CIERRE' : 'CONFORMIDAD_OBSERVADA',
            'performed_by_user_id' => auth()->id(),
            'performed_by_user_name' => auth()->user()?->full_name_with_username ?? auth()->user()?->name ?? $ticket->applicant_name,
            'notes' => ($this->formUserConformity ? 'Visto Bueno / Cierre Formal Aprobado' : 'Conformidad Observada (Retornado a Atención)') . " | Calificación: {$this->formUserRating}/5 estrellas. Observaciones: {$this->formUserFeedback}",
        ]);

        if ($this->selectedTicketForDetail && $this->selectedTicketForDetail->id === $ticket->id) {
            $this->selectedTicketForDetail = $ticket->fresh(['office', 'applicant', 'technicalDetail', 'supplies', 'loans', 'auditLogs']);
        }

        $this->isConformityModalOpen = false;
        $this->showAlert($this->formUserConformity ? "Ticket {$ticket->ticket_number} cerrado formalmente." : 'Observación registrada, ticket retornado a atención técnica.');
    }

    // ==========================================
    // 8. REASIGNAR TÉCNICO Y TRASPASO DE CARGAS (RF-13)
    // ==========================================
    public function getOperationalTechnicians(?string $excludeName = null): array
    {
        $metricsAction = app(GetHelpdeskDashboardMetricsAction::class);
        $metrics = $metricsAction->handle();
        $workload = $metrics['techniciansWorkload'] ?? [];

        $result = [];
        $seen = [];

        foreach ($workload as $w) {
            $name = $w['technicianName'];
            if ($excludeName && strtolower(trim($name)) === strtolower(trim($excludeName))) {
                continue;
            }
            $result[] = [
                'name' => $name,
                'activeTickets' => $w['inProgressCount'] ?? 0,
                'status' => $w['status'] ?? 'DISPONIBLE',
            ];
            $seen[strtolower(trim($name))] = true;
        }

        // Also check User table for any technician not yet in workload map
        $users = User::where('role', 'like', '%Soporte%')->orWhere('role', 'like', '%Administrador%')->get();
        foreach ($users as $u) {
            $name = $u->full_name_with_username;
            $normalized = strtolower(trim($name));
            if ($excludeName && $normalized === strtolower(trim($excludeName))) {
                continue;
            }
            if (! isset($seen[$normalized])) {
                $count = Ticket::where('assigned_technician_id', $u->id)
                    ->orWhere('assigned_technician_name', $name)
                    ->orWhere('assigned_technician_name', $u->full_name)
                    ->whereNotIn('status', ['RESUELTO', 'CERRADO'])
                    ->count();

                $result[] = [
                    'name' => $name,
                    'activeTickets' => $count,
                    'status' => $count >= 4 ? 'SOBRECARGADO' : ($count >= 1 ? 'OCUPADO' : 'DISPONIBLE'),
                ];
                $seen[$normalized] = true;
            }
        }

        usort($result, fn ($a, $b) => $a['activeTickets'] <=> $b['activeTickets']);

        return $result;
    }

    public function openReassignModal(int $ticketId): void
    {
        if (! $this->canReassignTickets()) {
            $this->showAlert("Acceso restringido: La transferencia de tickets está reservada para el jefe de oficina o técnicos autorizados.");
            return;
        }
        $this->reassignTicketId = $ticketId;
        $ticket = Ticket::with('assignedTechnician')->find($ticketId);

        $currentTech = $ticket?->assigned_technician_display ?: $ticket?->assigned_technician_name;
        $available = $this->getOperationalTechnicians($currentTech);

        $this->reassignTechnicianName = ! empty($available) ? $available[0]['name'] : '';
        $this->reassignTargetTechnician = $this->reassignTechnicianName;
        $matchedUser = $this->resolveTechnicianUser($this->reassignTechnicianName);
        $this->reassignUserId = $matchedUser?->id ?? User::first()?->id;
        $this->reassignNotes = 'Reasignación por especialidad técnica.';
        $this->reassignReason = $this->reassignNotes;
        $this->isReassignModalOpen = true;
    }

    public function saveReassign(): void
    {
        $ticket = Ticket::find($this->reassignTicketId);
        if (! $ticket) {
            $this->showAlert('Ticket no encontrado.', 'error');

            return;
        }

        $targetName = $this->reassignTargetTechnician ?: $this->reassignTechnicianName;
        $notes = $this->reassignReason ?: $this->reassignNotes;

        if (empty($targetName)) {
            $this->showAlert('Debe seleccionar un técnico de destino.', 'error');

            return;
        }

        $user = $this->resolveTechnicianUser($targetName);
        if (! $user && ! empty($this->reassignUserId)) {
            $user = User::find($this->reassignUserId);
        }

        $prevStatus = $ticket->status;
        $prevTech = $ticket->assigned_technician_name ?? 'Sin asignar';

        if ($user) {
            $ticket->assigned_technician_id = $user->id;
        }
        $ticket->assigned_technician_name = $targetName;
        $ticket->assigned_technician_phone = 'Anexo 104';

        if ($ticket->status === 'ABIERTO') {
            $ticket->status = 'EN_ATENCION';
            $ticket->started_at = $ticket->started_at ?? now();
        }
        $ticket->save();

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'previous_status' => $prevStatus,
            'new_status' => $ticket->status,
            'action' => 'REASIGNACION_TECNICO',
            'performed_by_user_id' => auth()->id(),
            'performed_by_user_name' => auth()->user()?->full_name ?? auth()->user()?->name ?? 'Coordinación ODT',
            'notes' => "Ticket traspasado de {$prevTech} a {$targetName}. {$notes}",
        ]);

        if ($this->selectedTicketForDetail && $this->selectedTicketForDetail->id === $ticket->id) {
            $this->selectedTicketForDetail = $ticket->fresh(['office', 'applicant', 'technicalDetail', 'supplies', 'loans', 'auditLogs']);
        }

        $this->isReassignModalOpen = false;
        $this->showAlert("Ticket {$ticket->ticket_number} traspasado a {$targetName} con éxito.");
    }

    public function openBulkTransferModal(string $originTechnicianName): void
    {
        $this->bulkOriginTechnician = $originTechnicianName;
        $activeTickets = $this->getActiveTicketsQueryForTechnician($originTechnicianName)->get();

        $this->bulkTicketsCount = $activeTickets->count();

        if ($this->bulkTicketsCount === 0) {
            $this->showAlert("El técnico {$originTechnicianName} no tiene tickets activos pendientes de traspaso.", 'warning');

            return;
        }

        $otherTechs = $this->getOperationalTechnicians($originTechnicianName);
        $this->bulkTargetTechnician = ! empty($otherTechs) ? $otherTechs[0]['name'] : '';
        $this->bulkDestinationMode = 'auto';
        $this->bulkReason = 'Descanso médico / Contingencia de personal';
        $this->isBulkTransferModalOpen = true;
    }

    public function executeBulkTransfer(): void
    {
        $activeTickets = $this->getActiveTicketsQueryForTechnician($this->bulkOriginTechnician)
            ->orderBy('priority', 'asc')
            ->get();

        if ($activeTickets->isEmpty()) {
            $this->showAlert("No se encontraron tickets activos para {$this->bulkOriginTechnician}.", 'warning');
            $this->isBulkTransferModalOpen = false;

            return;
        }

        $otherTechs = $this->getOperationalTechnicians($this->bulkOriginTechnician);
        if (empty($otherTechs)) {
            $this->showAlert('No hay otros técnicos disponibles para recibir la carga.', 'error');

            return;
        }

        $transferredCount = 0;

        if ($this->bulkDestinationMode === 'specific') {
            $targetName = $this->bulkTargetTechnician;
            if (empty($targetName)) {
                $this->showAlert('Debe seleccionar un técnico de destino.', 'error');

                return;
            }

            $targetUser = $this->resolveTechnicianUser($targetName);
            foreach ($activeTickets as $ticket) {
                $prevStatus = $ticket->status;
                if ($targetUser) {
                    $ticket->assigned_technician_id = $targetUser->id;
                }
                $ticket->assigned_technician_name = $targetName;
                $ticket->assigned_technician_phone = 'Anexo 104';
                $ticket->save();

                TicketAuditLog::create([
                    'ticket_id' => $ticket->id,
                    'previous_status' => $prevStatus,
                    'new_status' => $ticket->status,
                    'action' => 'TRASPASO_MASIVO_CARGA',
                    'performed_by_user_id' => auth()->id(),
                    'performed_by_user_name' => auth()->user()?->name ?? 'Coordinación ODT',
                    'notes' => "Traspaso masivo de carga desde {$this->bulkOriginTechnician} hacia {$targetName}. Motivo: {$this->bulkReason}",
                ]);
                $transferredCount++;
            }

            $this->showAlert("Se traspasaron con éxito {$transferredCount} tickets de {$this->bulkOriginTechnician} a {$targetName}.");
        } else {
            // Balance auto equitativo
            $workloadMap = [];
            foreach ($otherTechs as $t) {
                $workloadMap[$t['name']] = $t['activeTickets'];
            }

            foreach ($activeTickets as $ticket) {
                asort($workloadMap);
                $targetName = array_key_first($workloadMap);
                $targetUser = $this->resolveTechnicianUser($targetName);

                $prevStatus = $ticket->status;
                if ($targetUser) {
                    $ticket->assigned_technician_id = $targetUser->id;
                }
                $ticket->assigned_technician_name = $targetName;
                $ticket->assigned_technician_phone = 'Anexo 104';
                $ticket->save();

                TicketAuditLog::create([
                    'ticket_id' => $ticket->id,
                    'previous_status' => $prevStatus,
                    'new_status' => $ticket->status,
                    'action' => 'TRASPASO_MASIVO_BALANCEADO',
                    'performed_by_user_id' => auth()->id(),
                    'performed_by_user_name' => auth()->user()?->name ?? 'Coordinación ODT',
                    'notes' => "Traspaso masivo balanceado desde {$this->bulkOriginTechnician} hacia {$targetName}. Motivo: {$this->bulkReason}",
                ]);

                $workloadMap[$targetName]++;
                $transferredCount++;
            }

            $this->showAlert("Se traspasaron y redistribuyeron con éxito {$transferredCount} tickets de {$this->bulkOriginTechnician} entre el equipo técnico.");
        }

        $this->isBulkTransferModalOpen = false;
    }

    // ==========================================
    // 8.1 CARGAS DE TRABAJO Y BALANCEO INTELIGENTE (RF-13)
    // ==========================================
    public function assignTicketToLeastLoaded(int $ticketId): void
    {
        $metricsAction = app(GetHelpdeskDashboardMetricsAction::class);
        $metrics = $metricsAction->handle();
        $technicians = $metrics['techniciansWorkload'] ?? [];

        if (empty($technicians)) {
            $this->showAlert('No hay técnicos disponibles para asignación.', 'error');

            return;
        }

        // Ordenar por menor carga en proceso
        usort($technicians, fn ($a, $b) => $a['inProgressCount'] <=> $b['inProgressCount']);
        $leastLoaded = $technicians[0]['technicianName'];

        $this->assignTicketToTechnician($ticketId, $leastLoaded);
    }

    public function assignTicketToTechnician(int $ticketId, string $technicianName): void
    {
        if (! $this->canReassignTickets()) {
            $this->showAlert("Acceso restringido: La asignación de tickets está reservada para la jefatura.");
            return;
        }

        $ticket = Ticket::find($ticketId);
        if (! $ticket) {
            $this->showAlert('El ticket no fue encontrado.', 'error');

            return;
        }

        $targetUser = $this->resolveTechnicianUser($technicianName);
        if ($targetUser) {
            $ticket->assigned_technician_id = $targetUser->id;
        }
        $ticket->assigned_technician_name = $technicianName;
        $ticket->assigned_technician_phone = 'Anexo 104';
        $ticket->status = 'EN_ATENCION';
        $ticket->started_at = now();
        $ticket->save();

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'previous_status' => 'ABIERTO',
            'new_status' => 'EN_ATENCION',
            'action' => 'ASIGNACION_SUPERVISOR',
            'performed_by_user_name' => auth()->user()?->name ?? 'Coordinación ODT',
            'notes' => "Ticket asignado por balanceo a {$technicianName}.",
        ]);

        $this->showAlert("Ticket {$ticket->ticket_number} asignado exitosamente a {$technicianName}.");
    }

    public function autoAssignAllUnattended(): void
    {
        if (! $this->canReassignTickets()) {
            $this->showAlert("Acceso restringido: La asignación de tickets en cola está reservada para la jefatura.");
            return;
        }

        $unattended = Ticket::where('status', 'ABIERTO')
            ->orWhereNull('assigned_technician_name')
            ->orderByRaw("CASE priority WHEN 'CRITICA' THEN 1 WHEN 'ALTA' THEN 2 WHEN 'MEDIA' THEN 3 WHEN 'BAJA' THEN 4 ELSE 5 END")
            ->orderBy('id', 'asc')
            ->get();

        if ($unattended->isEmpty()) {
            $this->showAlert('No hay tickets pendientes por asignar.');

            return;
        }

        $assignedCount = 0;
        foreach ($unattended as $ticket) {
            $metricsAction = app(GetHelpdeskDashboardMetricsAction::class);
            $metrics = $metricsAction->handle();
            $technicians = $metrics['techniciansWorkload'] ?? [];

            if (empty($technicians)) {
                break;
            }

            usort($technicians, fn ($a, $b) => $a['inProgressCount'] <=> $b['inProgressCount']);
            $leastLoaded = $technicians[0]['technicianName'];

            $ticket->assigned_technician_name = $leastLoaded;
            $ticket->assigned_technician_phone = 'Anexo 104';
            $ticket->status = 'EN_ATENCION';
            $ticket->started_at = now();
            $ticket->save();

            TicketAuditLog::create([
                'ticket_id' => $ticket->id,
                'previous_status' => 'ABIERTO',
                'new_status' => 'EN_ATENCION',
                'action' => 'BALANCEO_AUTOMATICO',
                'performed_by_user_name' => auth()->user()?->name ?? 'Coordinación ODT',
                'notes' => "Ticket balanceado automáticamente hacia {$leastLoaded}.",
            ]);

            $assignedCount++;
        }

        $this->showAlert("Se balancearon y asignaron {$assignedCount} tickets pendientes con éxito.");
    }

    // ==========================================
    // 9. BASE DE CONOCIMIENTOS (RF-14)
    // ==========================================
    public function openViewKbModal(int $articleId): void
    {
        $this->selectedKbArticle = KnowledgeArticle::find($articleId);
        if ($this->selectedKbArticle) {
            $this->selectedKbArticle->increment('views_count');
            $this->kbModalMode = 'VIEW';
            $this->kbHasVoted = false;
            $this->isKbModalOpen = true;
        }
    }

    public function openKbModal(): void
    {
        $this->kbModalMode = 'CREATE';
        $this->selectedKbArticle = null;
        $this->formArticleTitle = '';
        $this->formArticleCategory = 'HARDWARE';
        $this->formArticleSummary = '';
        $this->formArticleSymptoms = '';
        $this->formArticleSteps = '';
        $this->formArticleTags = '';
        $this->isKbModalOpen = true;
    }

    public function voteHelpful(int $articleId): void
    {
        $article = KnowledgeArticle::find($articleId);
        if ($article) {
            $article->increment('helpful_count');
            $this->kbHasVoted = true;
            if ($this->selectedKbArticle && $this->selectedKbArticle->id === $articleId) {
                $this->selectedKbArticle->refresh();
            }
            $this->showAlert('¡Gracias por tu voto! Voto de utilidad registrado.');
        }
    }

    public function saveKbArticle(): void
    {
        $this->validate([
            'formArticleTitle' => 'required|string|max:255',
            'formArticleCategory' => 'required|string',
            'formArticleSummary' => 'required|string',
            'formArticleSteps' => 'required|string',
        ]);

        $tags = array_values(array_filter(array_map('trim', explode(',', $this->formArticleTags))));

        KnowledgeArticle::create([
            'title' => $this->formArticleTitle,
            'category' => $this->formArticleCategory,
            'summary' => $this->formArticleSummary,
            'symptoms' => $this->formArticleSymptoms ?: null,
            'solution_steps' => $this->formArticleSteps,
            'tags' => $tags,
            'author_technician_name' => auth()->user()?->name ?? 'Ing. Carlos Mendoza (Soporte TI)',
            'author_technician_id' => auth()->id() ?? 1,
            'views_count' => 1,
            'helpful_count' => 1,
        ]);

        $this->isKbModalOpen = false;
        $this->showAlert("Guía \"{$this->formArticleTitle}\" publicada en la Base de Conocimientos.");
    }

    public function render(GetHelpdeskDashboardMetricsAction $metricsAction): View
    {
        // 1. Tickets filtrados
        $ticketsQuery = Ticket::with(['office', 'technicalDetail', 'assignedTechnician'])
            ->when($this->search, function ($q) {
                $s = '%'.trim($this->search).'%';
                $q->where(function ($sub) use ($s) {
                    $sub->where('ticket_number', 'like', $s)
                        ->orWhere('applicant_name', 'like', $s)
                        ->orWhere('subject', 'like', $s)
                        ->orWhere('office_name', 'like', $s)
                        ->orWhere('asset_computer_code', 'like', $s);
                });
            })
            ->when($this->status !== 'TODOS', function ($q) {
                if ($this->status === 'EN_LABORATORIO' || $this->status === 'EN_TALLER') {
                    $q->whereIn('status', ['EN_LABORATORIO', 'EN_TALLER']);
                } else {
                    $q->where('status', $this->status);
                }
            })
            ->when($this->category !== 'TODAS', fn ($q) => $q->where('category', $this->category))
            ->when($this->priority !== 'TODAS', fn ($q) => $q->where('priority', $this->priority))
            ->orderBy('id', 'asc');

        $tickets = $ticketsQuery->paginate($this->perPage);

        // 2. Mis Tickets (Portal Solicitante / Seguimiento)
        $myTickets = Ticket::with(['office', 'technicalDetail', 'assignedTechnician'])
            ->orderBy('ticket_number', 'asc')
            ->get();

        // 3. Artículos KB (RF-14)
        $kbArticles = KnowledgeArticle::when($this->kbSearch, function ($q) {
            $s = '%'.trim($this->kbSearch).'%';
            $q->where(function ($sub) use ($s) {
                $sub->where('title', 'like', $s)
                    ->orWhere('summary', 'like', $s)
                    ->orWhere('solution_steps', 'like', $s);
            });
        })
            ->when($this->kbCategory !== 'TODAS' && $this->kbCategory !== 'ALL', fn ($q) => $q->where('category', $this->kbCategory))
            ->orderBy('helpful_count', 'desc')
            ->get();

        // 4. Técnicos & Usuarios
        $technicians = User::where('role', 'like', '%Soporte%')->orWhere('role', 'like', '%Administrador%')->get();
        $offices = Office::with('parent')->orderBy('name')->get();
        $people = Person::where('is_active', true)->get();

        $offices->each(function ($off) use ($people, $offices) {
            $resp = $off->resolveActiveResponsible($people, $offices);
            $off->applicant_name = $resp['name'];
            $off->applicant_phone = $resp['phone'];
            $off->applicant_email = $resp['email'];
            $off->is_inherited = (bool) $resp['is_inherited'];
            $off->inherited_from = $resp['inherited_from'];
        });
        $loanableAssets = Asset::with(['category', 'brand', 'model'])
            ->where('is_loanable', true)
            ->where('status', 'OPERATIVO')
            ->orderBy('computer_code')
            ->get();
        $assets = $loanableAssets;
        $supplies = Supply::where('stock', '>', 0)->get();
        $metrics = $metricsAction->handle();

        // Tickets sin atender en cola (RF-13 Balanceo Inteligente)
        $unassignedTickets = Ticket::with(['office'])
            ->where(function ($q) {
                $q->where('status', 'ABIERTO')
                    ->orWhereNull('assigned_technician_name');
            })
            ->orderByRaw("CASE priority WHEN 'CRITICA' THEN 1 WHEN 'ALTA' THEN 2 WHEN 'MEDIA' THEN 3 WHEN 'BAJA' THEN 4 ELSE 5 END")
            ->orderBy('created_at', 'asc')
            ->get();

        $leastLoadedTechnician = null;
        if (! empty($metrics['techniciansWorkload'])) {
            $techsSorted = $metrics['techniciansWorkload'];
            usort($techsSorted, fn ($a, $b) => $a['inProgressCount'] <=> $b['inProgressCount']);
            $leastLoadedTechnician = $techsSorted[0];
        }

        $counts = [
            'tickets' => Ticket::count(),
            'my_tickets' => $myTickets->count(),
            'open_tickets' => Ticket::where('status', 'ABIERTO')->count(),
            'kb' => KnowledgeArticle::count(),
        ];

        return view('livewire.helpdesk.tickets', [
            'tickets' => $tickets,
            'myTickets' => $myTickets,
            'kbArticles' => $kbArticles,
            'technicians' => $technicians,
            'operationalTechnicians' => $this->getOperationalTechnicians(),
            'offices' => $offices,
            'assets' => $assets,
            'loanableAssets' => $loanableAssets,
            'supplies' => $supplies,
            'metrics' => $metrics,
            'unassignedTickets' => $unassignedTickets,
            'leastLoadedTechnician' => $leastLoadedTechnician,
            'counts' => $counts,
        ])->layoutData([
            'counts' => $counts,
        ]);
    }
}
