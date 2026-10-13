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

    // 8. Modal Reasignar
    public bool $isReassignModalOpen = false;

    public ?int $reassignTicketId = null;

    public ?int $reassignUserId = null;

    public string $reassignNotes = 'Reasignación por especialidad técnica.';

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

    public function showAlert(string $message, string $type = 'success'): void
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->dispatch('show-toast', message: $message, type: $type);
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
        $user = auth()->user();
        $this->formApplicantName = $user ? ($user->full_name ?? $user->name) : 'ADMINISTRADOR DEL SISTEMA SIGM';
        $this->formApplicantPhone = 'Anexo 115';
        $this->formApplicantEmail = 'usuario@castilla.gob.pe';
        $this->formOfficeName = 'Secretaría General y Mesa de Partes';
        $this->formOfficeId = Office::where('name', 'like', '%Secretaría General%')->first()?->id ?? Office::first()?->id;
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
        if ($this->formOfficeId) {
            $office = Office::find($this->formOfficeId);
        } elseif ($this->formOfficeName) {
            $office = Office::where('name', 'like', '%'.$this->formOfficeName.'%')->first();
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
        $this->selectedTicketForDetail = Ticket::with(['technicalDetail', 'supplies', 'loans', 'auditLogs', 'office'])->find($ticketId);
        $this->isDetailModalOpen = true;
    }

    // ==========================================
    // 3. ATENCIÓN TÉCNICA
    // ==========================================
    public function takeTicket(int $ticketId): void
    {
        $ticket = Ticket::find($ticketId);
        if ($ticket) {
            $ticket->status = 'EN_ATENCION';
            $ticket->started_at = now();
            if (! $ticket->assigned_technician_name) {
                $user = auth()->user();
                $ticket->assigned_technician_name = $user ? ($user->full_name ?? $user->name) : 'Ing. Carlos Mendoza (Soporte TI)';
                $ticket->assigned_technician_phone = 'Anexo 104';
            }
            $ticket->save();

            TicketAuditLog::create([
                'ticket_id' => $ticket->id,
                'previous_status' => 'ABIERTO',
                'new_status' => 'EN_ATENCION',
                'action' => 'TICKET_TOMADO',
                'performed_by_user_name' => auth()->user()?->name ?? 'Ing. Carlos Mendoza (Soporte TI)',
                'notes' => 'El técnico ha tomado la atención del ticket.',
            ]);

            $this->showAlert("Ticket {$ticket->ticket_number} tomado en atención.");
        }
    }

    public function openTechnicalModal(int $ticketId): void
    {
        $this->technicalTicketId = $ticketId;
        $ticket = Ticket::with('technicalDetail')->find($ticketId);
        if ($ticket) {
            $this->formRealDiagnosis = $ticket->technicalDetail?->real_diagnosis ?? 'Falla de conectividad / configuración';
            $this->formSolutionApplied = $ticket->technicalDetail?->solution_applied ?? 'Reconfiguración de parámetros y prueba operativa.';
            $this->formTechnicalStatus = $ticket->status === 'ABIERTO' ? 'EN_ATENCION' : 'RESUELTO';
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
                'is_definitive_decommission' => $this->formIsDecommission,
                'decommission_reason' => $this->formIsDecommission ? $this->formDecommissionReason : null,
                'decommission_act_number' => $this->formIsDecommission ? 'ACTA-BAJA-'.date('Y').'-'.str_pad((string) $ticket->id, 4, '0', STR_PAD_LEFT) : null,
            ]
        );

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'previous_status' => $prevStatus,
            'new_status' => $this->formTechnicalStatus,
            'action' => 'ACTUALIZACION_TECNICA',
            'performed_by_user_name' => 'Técnico ODT',
            'notes' => 'Diagnóstico y solución técnica registrados.',
        ]);

        if ($this->formPublishToKb && ! empty($this->formKbTitle)) {
            KnowledgeArticle::create([
                'title' => $this->formKbTitle,
                'category' => $ticket->category,
                'summary' => $this->formRealDiagnosis,
                'solution_steps' => $this->formSolutionApplied,
                'author_technician_name' => 'Área Técnica TI',
                'helpful_count' => 1,
            ]);
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
        $this->suppliesTicketId = $ticketId;
        $this->selectedSupplyId = Supply::first()?->id;
        $this->formSupplyQuantity = 1;
        $this->formSupplyNotes = 'Repuesto instalado en la dependencia solicitante.';
        $this->isSuppliesModalOpen = true;
    }

    public function saveTicketSupply(): void
    {
        $supply = Supply::find($this->selectedSupplyId);
        if (! $supply) {
            return;
        }

        TicketSupply::create([
            'ticket_id' => $this->suppliesTicketId,
            'supply_id' => $supply->id,
            'supply_name' => $supply->name,
            'quantity' => $this->formSupplyQuantity,
            'notes' => $this->formSupplyNotes,
        ]);

        $supply->stock = max(0, $supply->stock - $this->formSupplyQuantity);
        $supply->save();

        $this->isSuppliesModalOpen = false;
        $this->showAlert("Insumo {$supply->name} asignado y descontado de almacén ITAM.");
    }

    // ==========================================
    // 5. EQUIPO PROVISIONAL
    // ==========================================
    public function openProvisionalModal(int $ticketId): void
    {
        $this->provisionalTicketId = $ticketId;
        $this->selectedProvisionalAssetId = Asset::where('is_loanable', true)->first()?->id ?? Asset::first()?->id;
        $this->isProvisionalModalOpen = true;
    }

    public function saveProvisionalLoan(): void
    {
        $asset = Asset::find($this->selectedProvisionalAssetId);
        if (! $asset) {
            return;
        }

        TicketLoan::create([
            'ticket_id' => $this->provisionalTicketId,
            'provisional_asset_id' => $asset->id,
            'provisional_computer_code' => $asset->computer_code,
            'status' => 'ACTIVO',
            'notes' => $this->formProvisionalNotes,
        ]);

        $asset->status = 'PRESTADO';
        $asset->save();

        $this->isProvisionalModalOpen = false;
        $this->showAlert("Equipo provisional {$asset->computer_code} cedido temporalmente.");
    }

    // ==========================================
    // 6. ACTA DE BAJA PATRIMONIAL
    // ==========================================
    public function openDecommissionModal(int $ticketId): void
    {
        $this->decommissionTicket = Ticket::with(['technicalDetail', 'office'])->find($ticketId);
        $this->isDecommissionModalOpen = true;
    }

    // ==========================================
    // 7. CONFORMIDAD DE USUARIO
    // ==========================================
    public function openConformityModal(int $ticketId): void
    {
        $this->conformityTicketId = $ticketId;
        $this->conformityTicket = Ticket::with(['technicalDetail', 'office'])->find($ticketId);
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

        $ticket->user_conformity = $this->formUserConformity;
        $ticket->user_rating = $this->formUserRating;
        $ticket->user_feedback = $this->formUserFeedback;
        $ticket->status = $this->formUserConformity ? 'CERRADO' : 'EN_ATENCION';
        if ($this->formUserConformity) {
            $ticket->closed_at = now();
        }
        $ticket->save();

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'new_status' => $ticket->status,
            'action' => 'CONFORMIDAD_USUARIO',
            'performed_by_user_name' => $ticket->applicant_name,
            'notes' => "Calificación: {$this->formUserRating} estrellas. {$this->formUserFeedback}",
        ]);

        $this->isConformityModalOpen = false;
        $this->showAlert($this->formUserConformity ? "Ticket {$ticket->ticket_number} cerrado formalmente." : 'Observación registrada, ticket retornado a atención técnica.');
    }

    // ==========================================
    // 8. REASIGNAR TÉCNICO
    // ==========================================
    public function openReassignModal(int $ticketId): void
    {
        $this->reassignTicketId = $ticketId;
        $this->reassignUserId = User::first()?->id;
        $this->isReassignModalOpen = true;
    }

    public function saveReassign(): void
    {
        $ticket = Ticket::find($this->reassignTicketId);
        $user = User::find($this->reassignUserId);
        if (! $ticket || ! $user) {
            return;
        }

        $ticket->assigned_technician_id = $user->id;
        $ticket->assigned_technician_name = $user->full_name;
        $ticket->status = 'EN_ATENCION';
        $ticket->save();

        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'new_status' => 'EN_ATENCION',
            'action' => 'REASIGNACION_TECNICO',
            'performed_by_user_name' => 'Coordinación ODT',
            'notes' => "Ticket reasignado a {$user->full_name}. {$this->reassignNotes}",
        ]);

        $this->isReassignModalOpen = false;
        $this->showAlert("Ticket {$ticket->ticket_number} reasignado a {$user->full_name}.");
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
        $ticketsQuery = Ticket::with(['office', 'technicalDetail'])
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
        $myTickets = Ticket::with(['office', 'technicalDetail'])
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
        $offices = Office::orderBy('name')->get();
        $assets = Asset::orderBy('computer_code')->get();
        $supplies = Supply::where('stock', '>', 0)->get();
        $metrics = $metricsAction->handle();

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
            'offices' => $offices,
            'assets' => $assets,
            'supplies' => $supplies,
            'metrics' => $metrics,
            'counts' => $counts,
        ])->layoutData([
            'counts' => $counts,
        ]);
    }
}
