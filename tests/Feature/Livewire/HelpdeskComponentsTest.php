<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\Helpdesk\NewTicket as NewTicketComponent;
use App\Livewire\Helpdesk\Tickets as TicketsComponent;
use App\Models\Helpdesk\KnowledgeArticle;
use App\Models\Helpdesk\Ticket;
use App\Models\Office;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HelpdeskComponentsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $admin = \App\Models\User::where('username', 'root')->first();
        if ($admin) {
            $this->actingAs($admin);
        }
    }

    public function test_renders_helpdesk_dashboard_successfully(): void
    {
        $response = $this->get('/helpdesk');

        $response->assertStatus(200);
        $response->assertSee('TOTAL TICKETS');
        $response->assertSee('1. ABIERTOS');
        $response->assertSee('Registrar Incidencia');
    }

    public function test_renders_tickets_queue_and_creates_ticket(): void
    {
        $response = $this->get('/helpdesk/tickets');

        $response->assertStatus(200);
        $response->assertSee('Bandeja de Tickets');

        $office = Office::first();

        Livewire::test(TicketsComponent::class)
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->set('formApplicantName', 'Julio Espinoza')
            ->set('formOfficeId', $office?->id)
            ->set('formSubject', 'Falla de conexión a internet')
            ->set('formDescription', 'No carga el sistema SIGM en el terminal')
            ->call('saveTicket')
            ->assertSet('showCreateModal', false);

        $this->assertDatabaseHas('helpdesk_tickets', [
            'applicant_name' => 'Julio Espinoza',
            'subject' => 'Falla de conexión a internet',
            'status' => 'ABIERTO',
        ]);
    }

    public function test_takes_ticket_and_adds_technical_detail(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-2026-9999',
            'applicant_name' => 'Ana Gomez',
            'office_name' => 'Alcaldía',
            'category' => 'HARDWARE',
            'priority' => 'ALTA',
            'subject' => 'PC reinicia inesperadamente',
            'description' => 'Pantalla azul tras 10 minutos',
            'status' => 'ABIERTO',
        ]);

        Livewire::test(TicketsComponent::class)
            ->call('takeTicket', $ticket->id);

        $this->assertEquals('EN_ATENCION', $ticket->fresh()->status);

        Livewire::test(TicketsComponent::class)
            ->call('openTechnicalModal', $ticket->id)
            ->assertSet('showTechnicalModal', true)
            ->set('formRealDiagnosis', 'Sobrecalentamiento de procesador')
            ->set('formSolutionApplied', 'Limpieza y aplicación de pasta térmica')
            ->set('formTechnicalStatus', 'RESUELTO')
            ->call('saveTechnicalDetail')
            ->assertSet('showTechnicalModal', false);

        $this->assertEquals('RESUELTO', $ticket->fresh()->status);
        $this->assertDatabaseHas('helpdesk_ticket_technical_details', [
            'ticket_id' => $ticket->id,
            'real_diagnosis' => 'Sobrecalentamiento de procesador',
        ]);
    }

    public function test_renders_knowledge_base_and_increments_views(): void
    {
        $article = KnowledgeArticle::first();

        $response = $this->get('/helpdesk/kb');
        $response->assertRedirect('/helpdesk?tab=knowledge');

        if ($article) {
            $initialViews = $article->views_count;
            Livewire::withQueryParams(['tab' => 'knowledge'])
                ->test(TicketsComponent::class)
                ->assertSee($article->title)
                ->call('openViewKbModal', $article->id)
                ->assertSet('isKbModalOpen', true);

            $this->assertEquals($initialViews + 1, $article->fresh()->views_count);
        }
    }

    public function test_new_ticket_page_prefills_office_and_applicant_by_query_parameters(): void
    {
        $response = $this->get('/soporte/nuevo-ticket?oficina=GERENCIA%20MUNICIPAL&dni=02821801');
        $response->assertStatus(200);

        Livewire::withQueryParams([
            'oficina' => 'GERENCIA MUNICIPAL',
            'dni' => '02821801',
        ])
            ->test(NewTicketComponent::class)
            ->assertSet('officeName', 'GERENCIA MUNICIPAL')
            ->assertSet('applicantName', 'CARLOS EDUARDO ROJAS MATIAS');
    }

    public function test_new_ticket_page_keeps_all_form_fields_intact_and_renders_tracking_column(): void
    {
        $response = $this->get('/soporte/nuevo-ticket');
        $response->assertStatus(200);

        // Assert all existing form fields are completely intact
        $response->assertSee('FORMULARIO DE SOPORTE TÉCNICO (SRS RF-01 / RF-04)');
        $response->assertSee('1. DATOS DE IDENTIFICACIÓN (RF-02)');
        $response->assertSee('Nombre del Funcionario / Custodio *:');
        $response->assertSee('Oficina o Dependencia Municipal *:');
        $response->assertSee('2. ESCANEO DE CÓDIGO QR DEL EQUIPO (RF-03)');
        $response->assertSee('Tipo de Inconveniente o Falla (Categoría)');
        $response->assertSee('Nivel de Severidad / Impacto en sus labores:');
        $response->assertSee('Asunto breve con sus propias palabras *:');
        $response->assertSee('Detalle del problema (¿Qué sucedió y cuándo?) *:');
        $response->assertSee('Foto o Captura de Pantalla:');
        $response->assertSee('Enviar Ticket a Soporte Técnico');

        // Assert right side tracking column is rendered
        $response->assertSee('Seguimiento de Tickets del Área');
        $response->assertSee('Técnico Asignado de Atención');

        $ticket = Ticket::where('office_name', 'like', '%REGIDORES%')->where('status', 'RESUELTO')->whereNull('user_conformity')->first();
        $this->assertNotNull($ticket);

        $jefeOdt = User::where('role', 'like', '%Jefe%')->first()
            ?? User::where('username', 'root')->first();

        Livewire::actingAs($jefeOdt)
            ->test(NewTicketComponent::class)
            ->set('officeName', 'OFICINA DE REGIDORES')
            ->assertSee($ticket->ticket_number)
            ->assertSee('Dar Visto Bueno / Cerrar Ticket')
            ->call('openConformityModal', $ticket->id)
            ->assertSet('isConformityModalOpen', true)
            ->call('setRating', 5)
            ->call('setConformity', true)
            ->call('saveUserConformity')
            ->assertSet('isConformityModalOpen', false);

        $fresh = $ticket->fresh();
        $this->assertEquals('CERRADO', $fresh->status);
        $this->assertTrue((bool) $fresh->user_conformity);
        $this->assertEquals(5, $fresh->user_rating);
    }

    public function test_responsible_with_multiple_hierarchy_offices_shows_offices_in_combo_with_primary_as_default(): void
    {
        // Asegurar que las oficinas y jerarquías existan en el entorno de pruebas
        $gat = Office::firstOrCreate(
            ['acronym' => 'GAT'],
            ['name' => 'GERENCIA DE ADMINISTRACIÓN TRIBUTARIA', 'level' => 1, 'is_active' => true]
        );

        $sgt = Office::firstOrCreate(
            ['acronym' => 'SGT'],
            ['name' => 'SUBGERENCIA DE TRIBUTACIÓN', 'parent_id' => $gat->id, 'level' => 2, 'is_active' => true]
        );

        $person = Person::firstOrCreate(
            ['document_number' => '42747895'],
            [
                'type' => 'PERSONAL',
                'document_type' => 'DNI',
                'first_name' => 'CINTHYA KATTY',
                'paternal_surname' => 'FERRO',
                'maternal_surname' => 'COLUMBUS',
                'position' => 'GERENTE DE ADMINISTRACION TRIBUTARIA',
                'office' => '[GAT] GERENCIA DE ADMINISTRACIÓN TRIBUTARIA',
                'is_active' => true,
                'labor_status' => 'ACTIVO',
            ]
        );

        Livewire::test(NewTicketComponent::class)
            ->set('applicantName', 'CINTHYA KATTY FERRO COLUMBUS')
            ->assertSet('officeName', 'GERENCIA DE ADMINISTRACIÓN TRIBUTARIA')
            ->assertSee('GERENCIA DE ADMINISTRACIÓN TRIBUTARIA')
            ->assertSee('Principal')
            ->assertSee('SUBGERENCIA DE TRIBUTACIÓN')
            ->assertSee('Encargada por jerarquía');
    }

    public function test_workload_tab_renders_unassigned_tickets_and_assigns_to_least_loaded_technician(): void
    {
        $unassigned = Ticket::create([
            'ticket_number' => 'TCK-2026-TEST-UNASSIGNED',
            'applicant_name' => 'Mario Vargas',
            'office_name' => 'Procuraduría',
            'category' => 'RED_INTERNET',
            'priority' => 'ALTA',
            'subject' => 'Corte total de internet en oficina',
            'description' => 'Ninguna máquina navega',
            'status' => 'ABIERTO',
            'assigned_technician_name' => null,
        ]);

        Livewire::test(TicketsComponent::class)
            ->call('setTab', 'workload')
            ->assertSee('Tickets Sin Atender en Cola')
            ->assertSee('TCK-2026-TEST-UNASSIGNED')
            ->assertSee('Corte total de internet en oficina')
            ->call('assignTicketToLeastLoaded', $unassigned->id);

        $fresh = $unassigned->fresh();
        $this->assertEquals('EN_ATENCION', $fresh->status);
        $this->assertNotNull($fresh->assigned_technician_name);
        $this->assertEquals('JAIME HENRY SANDOVAL NIEVES (soporte4)', $fresh->assigned_technician_name);
    }

    public function test_reassign_ticket_modal_and_execution_creates_audit_log(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-2026-TEST-REASSIGN',
            'applicant_name' => 'Sara Morales',
            'office_name' => 'Secretaría General',
            'category' => 'SOFTWARE',
            'priority' => 'MEDIA',
            'subject' => 'Instalación de firma digital',
            'description' => 'Requiere configurar certificado',
            'status' => 'EN_ATENCION',
            'assigned_technician_name' => 'Téc. Juan Pérez',
        ]);

        Livewire::test(TicketsComponent::class)
            ->call('openReassignModal', $ticket->id)
            ->assertSet('isReassignModalOpen', true)
            ->assertSet('reassignTicketId', $ticket->id)
            ->set('reassignTargetTechnician', 'Téc. Carlos Mendoza')
            ->set('reassignReason', 'Especialidad técnica requerida')
            ->call('saveReassign')
            ->assertSet('isReassignModalOpen', false)
            ->assertDispatched('toast');

        $fresh = $ticket->fresh();
        $this->assertEquals('Téc. Carlos Mendoza', $fresh->assigned_technician_name);

        $hasReassignHistory = $fresh->histories()
            ->where('action', 'like', '%REASIGN%')
            ->where('notes', 'like', '%Especialidad técnica requerida%')
            ->exists();
        $this->assertTrue($hasReassignHistory);
    }

    public function test_bulk_transfer_workload_for_contingency(): void
    {
        $t1 = Ticket::create([
            'ticket_number' => 'TCK-2026-BULK-1',
            'applicant_name' => 'Luis Ramos',
            'office_name' => 'Alcaldía',
            'category' => 'HARDWARE',
            'priority' => 'ALTA',
            'subject' => 'Impresora trabada',
            'description' => 'Atasco de papel',
            'status' => 'EN_ATENCION',
            'assigned_technician_name' => 'Téc. Ana Gómez',
        ]);

        $t2 = Ticket::create([
            'ticket_number' => 'TCK-2026-BULK-2',
            'applicant_name' => 'Rosa Flores',
            'office_name' => 'Logística',
            'category' => 'RED_INTERNET',
            'priority' => 'URGENTE',
            'subject' => 'Punto de red dañado',
            'description' => 'Cable roto',
            'status' => 'PENDIENTE',
            'assigned_technician_name' => 'Téc. Ana Gómez',
        ]);

        Livewire::test(TicketsComponent::class)
            ->call('openBulkTransferModal', 'Téc. Ana Gómez')
            ->assertSet('isBulkTransferModalOpen', true)
            ->assertSet('bulkOriginTechnician', 'Téc. Ana Gómez')
            ->assertSet('bulkTicketsCount', 2)
            ->set('bulkDestinationMode', 'specific')
            ->set('bulkTargetTechnician', 'Téc. Juan Pérez')
            ->set('bulkReason', 'Descanso médico / Licencia de salud')
            ->call('executeBulkTransfer')
            ->assertSet('isBulkTransferModalOpen', false)
            ->assertDispatched('toast');

        $this->assertEquals('Téc. Juan Pérez', $t1->fresh()->assigned_technician_name);
        $this->assertEquals('Téc. Juan Pérez', $t2->fresh()->assigned_technician_name);

        $this->assertTrue($t1->fresh()->histories()->where('action', 'like', '%TRASPASO%')->exists());
        $this->assertTrue($t2->fresh()->histories()->where('action', 'like', '%TRASPASO%')->exists());
    }

    public function test_assigning_warehouse_supply_to_ticket_persists_supply_code_and_decrements_stock(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-2026-SUPPLY-TEST',
            'applicant_name' => 'Carlos Ramos',
            'office_name' => 'Sistemas',
            'category' => 'RED_INTERNET',
            'priority' => 'MEDIA',
            'subject' => 'Cable dañado',
            'description' => 'Reemplazo de cable de red',
            'status' => 'EN_ATENCION',
        ]);

        $supply = \App\Models\Itam\Supply::first();
        $this->assertNotNull($supply);
        $initialStock = $supply->stock;

        Livewire::test(TicketsComponent::class)
            ->call('openSuppliesModal', $ticket->id)
            ->assertSet('isSuppliesModalOpen', true)
            ->set('selectedSupplyId', $supply->id)
            ->set('formSupplyQuantity', 1)
            ->set('formSupplyNotes', 'Repuesto instalado en la dependencia solicitante.')
            ->call('saveTicketSupply')
            ->assertSet('isSuppliesModalOpen', false);

        // Verificar persistencia en base de datos sin errores de constraint
        $ticketSupply = \App\Models\Helpdesk\TicketSupply::where('ticket_id', $ticket->id)->first();
        $this->assertNotNull($ticketSupply);
        $this->assertEquals($supply->id, $ticketSupply->supply_id);
        $this->assertEquals($supply->code, $ticketSupply->supply_code);
        $this->assertEquals($supply->name, $ticketSupply->supply_name);
        $this->assertEquals(1, $ticketSupply->quantity);

        $this->assertEquals($initialStock - 1, $supply->fresh()->stock);
    }

    public function test_assigning_provisional_asset_to_ticket_persists_loan(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-2026-LOAN-TEST',
            'applicant_name' => 'María Torres',
            'office_name' => 'Contabilidad',
            'category' => 'HARDWARE',
            'priority' => 'ALTA',
            'subject' => 'PC no enciende',
            'description' => 'Falla de fuente de poder',
            'status' => 'EN_ATENCION',
        ]);

        $asset = \App\Models\Itam\Asset::where('is_loanable', true)->where('status', 'OPERATIVO')->first()
            ?? \App\Models\Itam\Asset::first();
        $asset->update(['is_loanable' => true, 'status' => 'OPERATIVO']);
        $this->assertNotNull($asset);

        Livewire::test(TicketsComponent::class)
            ->call('openProvisionalModal', $ticket->id)
            ->assertSet('isProvisionalModalOpen', true)
            ->set('selectedProvisionalAssetId', $asset->id)
            ->set('formProvisionalNotes', 'Equipo de reemplazo temporal.')
            ->call('saveProvisionalLoan')
            ->assertSet('isProvisionalModalOpen', false);

        $ticketLoan = \App\Models\Helpdesk\TicketLoan::where('ticket_id', $ticket->id)->first();
        $this->assertNotNull($ticketLoan);
        $this->assertEquals($asset->id, $ticketLoan->temporary_asset_id);
        $this->assertEquals($asset->computer_code, $ticketLoan->temporary_asset_code);
        $this->assertEquals('PRESTADO', $asset->fresh()->status);
    }
    public function test_pausing_and_resuming_ticket_attention_creates_audit_trail_and_persists_reason(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-2026-PAUSE-TEST',
            'applicant_name' => 'Luis Ramos',
            'office_name' => 'Rentas',
            'category' => 'REDES',
            'priority' => 'MEDIA',
            'subject' => 'Cable de red dañado',
            'description' => 'Requiere conector RJ45',
            'status' => 'EN_ATENCION',
        ]);

        // 1. Pausar ticket usando modal rápido de pausa
        Livewire::test(TicketsComponent::class)
            ->call('openPauseModal', $ticket->id)
            ->assertSet('isPauseModalOpen', true)
            ->set('formPauseReason', 'En espera de repuestos / insumos de almacén')
            ->call('saveTicketPause')
            ->assertSet('isPauseModalOpen', false);

        $this->assertEquals('EN_PAUSA', $ticket->fresh()->status);
        $this->assertDatabaseHas('helpdesk_ticket_technical_details', [
            'ticket_id' => $ticket->id,
            'pause_reason' => 'En espera de repuestos / insumos de almacén',
        ]);
        $this->assertDatabaseHas('helpdesk_ticket_audit_logs', [
            'ticket_id' => $ticket->id,
            'action' => 'PAUSA_ATENCION',
            'new_status' => 'EN_PAUSA',
        ]);

        // 2. Reanudar ticket
        Livewire::test(TicketsComponent::class)
            ->call('resumeTicket', $ticket->id);

        $this->assertEquals('EN_ATENCION', $ticket->fresh()->status);
        $this->assertDatabaseHas('helpdesk_ticket_audit_logs', [
            'ticket_id' => $ticket->id,
            'action' => 'REANUDACION_ATENCION',
            'new_status' => 'EN_ATENCION',
        ]);
    }
    public function test_technician_cannot_modify_ticket_assigned_to_another_technician(): void
    {
        $tech1 = User::where('username', 'soporte1')->first();
        $tech2 = User::where('username', 'soporte2')->first();
        $this->assertNotNull($tech1);
        $this->assertNotNull($tech2);

        $ticket = Ticket::create([
            'ticket_number' => 'TCK-2026-SECURITY-1',
            'applicant_name' => 'Rosa Valdivia',
            'office_name' => 'Tesorería',
            'category' => 'SOFTWARE',
            'priority' => 'ALTA',
            'subject' => 'Sistema SIAF bloqueado',
            'description' => 'Error de conexión',
            'status' => 'EN_ATENCION',
            'assigned_technician_id' => $tech2->id,
            'assigned_technician_name' => $tech2->full_name_with_username,
        ]);

        // Tech 1 intenta abrir o modificar ticket asignado a Tech 2
        Livewire::actingAs($tech1)
            ->test(TicketsComponent::class)
            ->call('openTechnicalModal', $ticket->id)
            ->assertSet('showTechnicalModal', false)
            ->call('openPauseModal', $ticket->id)
            ->assertSet('isPauseModalOpen', false)
            ->call('openSuppliesModal', $ticket->id)
            ->assertSet('isSuppliesModalOpen', false)
            ->call('openProvisionalModal', $ticket->id)
            ->assertSet('isProvisionalModalOpen', false);

        // Tech 2 (dueño del ticket) SÍ puede abrir y gestionar
        Livewire::actingAs($tech2)
            ->test(TicketsComponent::class)
            ->call('openTechnicalModal', $ticket->id)
            ->assertSet('showTechnicalModal', true);

        // Administrador Central (root) SÍ puede gestionar cualquier ticket
        $root = User::where('username', 'root')->first();
        $this->assertNotNull($root);

        Livewire::actingAs($root)
            ->test(TicketsComponent::class)
            ->call('openTechnicalModal', $ticket->id)
            ->assertSet('showTechnicalModal', true);
    }

    public function test_technician_cannot_reassign_ticket_without_permission(): void
    {
        $tech1 = User::where('username', 'soporte1')->first();
        $this->assertNotNull($tech1);

        $ticket = Ticket::create([
            'ticket_number' => 'TCK-2026-SECURITY-2',
            'applicant_name' => 'Hugo Chavez',
            'office_name' => 'Logística',
            'category' => 'REDES',
            'priority' => 'MEDIA',
            'subject' => 'Sin acceso a red',
            'description' => 'Falla de cable',
            'status' => 'EN_ATENCION',
            'assigned_technician_id' => $tech1->id,
            'assigned_technician_name' => $tech1->full_name_with_username,
        ]);

        // Tech 1 (rol Técnico de Soporte e ITAM sin permiso de asignación) NO puede reasignar
        Livewire::actingAs($tech1)
            ->test(TicketsComponent::class)
            ->call('openReassignModal', $ticket->id)
            ->assertSet('isReassignModalOpen', false);

        // Administrador Central SÍ puede transferir
        $root = User::where('username', 'root')->first();
        Livewire::actingAs($root)
            ->test(TicketsComponent::class)
            ->call('openReassignModal', $ticket->id)
            ->assertSet('isReassignModalOpen', true);
    }
    public function test_conformity_cannot_be_granted_to_open_or_in_progress_tickets(): void
    {
        $openTicket = Ticket::create([
            'ticket_number' => 'TCK-CONF-TEST-OPEN',
            'applicant_name' => 'Usuario Test',
            'office_name' => 'OFICINA DE REGIDORES',
            'category' => 'HARDWARE',
            'priority' => 'MEDIA',
            'subject' => 'Ticket abierto para prueba de conformidad',
            'description' => 'Descripción de prueba',
            'status' => 'ABIERTO',
        ]);

        $inProgressTicket = Ticket::create([
            'ticket_number' => 'TCK-CONF-TEST-PROGRESS',
            'applicant_name' => 'Usuario Test 2',
            'office_name' => 'OFICINA DE REGIDORES',
            'category' => 'SOFTWARE',
            'priority' => 'MEDIA',
            'subject' => 'Ticket en atención para prueba de conformidad',
            'description' => 'Descripción de prueba',
            'status' => 'EN_ATENCION',
        ]);

        $admin = User::where('username', 'root')->first();

        // En NewTicket: No debe poder abrir el modal de conformidad para ticket ABIERTO o EN_ATENCION (incluso como admin/jefe)
        Livewire::actingAs($admin)
            ->test(NewTicketComponent::class)
            ->set('officeName', 'OFICINA DE REGIDORES')
            ->call('openConformityModal', $openTicket->id)
            ->assertSet('isConformityModalOpen', false)
            ->call('openConformityModal', $inProgressTicket->id)
            ->assertSet('isConformityModalOpen', false);

        // En Tickets (Panel Central): Tampoco debe permitir abrir el modal
        $admin = User::where('username', 'root')->first();
        Livewire::actingAs($admin)
            ->test(TicketsComponent::class)
            ->call('openConformityModal', $openTicket->id)
            ->assertSet('isConformityModalOpen', false)
            ->call('openConformityModal', $inProgressTicket->id)
            ->assertSet('isConformityModalOpen', false);

        // Intentar guardar conformidad directamente debe ser rechazado
        Livewire::actingAs($admin)
            ->test(TicketsComponent::class)
            ->set('conformityTicketId', $openTicket->id)
            ->call('saveUserConformity');

        $this->assertEquals('ABIERTO', $openTicket->fresh()->status);
        $this->assertNull($openTicket->fresh()->user_conformity);
    }

    public function test_conformity_cannot_be_granted_twice_when_already_conforming(): void
    {
        $closedTicket = Ticket::create([
            'ticket_number' => 'TCK-CONF-TEST-CLOSED',
            'applicant_name' => 'Usuario Conforme',
            'office_name' => 'OFICINA DE REGIDORES',
            'category' => 'HARDWARE',
            'priority' => 'BAJA',
            'subject' => 'Ticket ya cerrado con visto bueno previo',
            'description' => 'Descripción de prueba',
            'status' => 'CERRADO',
            'user_conformity' => true,
            'user_rating' => 5,
            'user_feedback' => 'Atendido con éxito',
        ]);

        $admin = User::where('username', 'root')->first();
        Livewire::actingAs($admin)
            ->test(NewTicketComponent::class)
            ->set('officeName', 'OFICINA DE REGIDORES')
            ->call('openConformityModal', $closedTicket->id)
            ->assertSet('isConformityModalOpen', false);

        $admin = User::where('username', 'root')->first();
        Livewire::actingAs($admin)
            ->test(TicketsComponent::class)
            ->call('openConformityModal', $closedTicket->id)
            ->assertSet('isConformityModalOpen', false);
    }
    public function test_visto_bueno_can_be_granted_by_jefe_oficina_odt_and_root(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-CONF-AUTH-1',
            'applicant_name' => 'Usuario Prueba',
            'office_name' => 'OFICINA DE REGIDORES',
            'category' => 'HARDWARE',
            'priority' => 'MEDIA',
            'subject' => 'Prueba de visto bueno por jefe y root',
            'description' => 'Descripción de prueba',
            'status' => 'RESUELTO',
        ]);

        $jefe = User::where('role', 'like', '%Jefe%')->first();
        if (! $jefe) {
            $jefe = User::create([
                'username' => 'jefe_odt_test',
                'full_name' => 'Jaime Sandoval (Jefe ODT)',
                'email' => 'jefe@castilla.gob.pe',
                'password' => bcrypt('password'),
                'role' => 'Jefe de Oficina / Soporte TI',
                'allowed_modules' => ['admin', 'itam', 'helpdesk'],
                'is_active' => true,
            ]);
        }
        $this->assertNotNull($jefe);
        $this->assertTrue($jefe->canApproveTicketConformity());

        // 1. Jefe de oficina ODT otorga visto bueno
        Livewire::actingAs($jefe)
            ->test(TicketsComponent::class)
            ->call('openConformityModal', $ticket->id)
            ->assertSet('isConformityModalOpen', true)
            ->call('setRating', 5)
            ->call('setConformity', true)
            ->call('saveUserConformity')
            ->assertSet('isConformityModalOpen', false);

        $this->assertEquals('CERRADO', $ticket->fresh()->status);
        $this->assertTrue((bool) $ticket->fresh()->user_conformity);

        // 2. Usuario root también tiene la facultad para otorgar visto bueno
        $ticketRoot = Ticket::create([
            'ticket_number' => 'TCK-CONF-AUTH-ROOT',
            'applicant_name' => 'Usuario Prueba 2',
            'office_name' => 'OFICINA DE REGIDORES',
            'category' => 'REDES',
            'priority' => 'MEDIA',
            'subject' => 'Prueba de visto bueno por root',
            'description' => 'Descripción de prueba',
            'status' => 'RESUELTO',
        ]);
        $root = User::where('username', 'root')->first();
        $this->assertTrue($root->canApproveTicketConformity());

        Livewire::actingAs($root)
            ->test(TicketsComponent::class)
            ->call('openConformityModal', $ticketRoot->id)
            ->assertSet('isConformityModalOpen', true)
            ->call('setRating', 5)
            ->call('setConformity', true)
            ->call('saveUserConformity')
            ->assertSet('isConformityModalOpen', false);

        $this->assertEquals('CERRADO', $ticketRoot->fresh()->status);
        $this->assertTrue((bool) $ticketRoot->fresh()->user_conformity);
    }

    public function test_visto_bueno_cannot_be_granted_by_unauthorized_technician_or_guest(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-CONF-AUTH-2',
            'applicant_name' => 'Usuario Prueba',
            'office_name' => 'OFICINA DE REGIDORES',
            'category' => 'HARDWARE',
            'priority' => 'MEDIA',
            'subject' => 'Prueba de restricción a técnico sin autoridad',
            'description' => 'Descripción de prueba',
            'status' => 'RESUELTO',
        ]);

        $techWithoutAuth = User::where('username', 'soporte1')->first();
        $this->assertNotNull($techWithoutAuth);
        $this->assertFalse($techWithoutAuth->canApproveTicketConformity());

        // Técnico sin permiso intenta abrir modal de visto bueno -> Bloqueado
        Livewire::actingAs($techWithoutAuth)
            ->test(TicketsComponent::class)
            ->call('openConformityModal', $ticket->id)
            ->assertSet('isConformityModalOpen', false);

        // Técnico sin permiso intenta guardar visto bueno directamente -> Bloqueado
        Livewire::actingAs($techWithoutAuth)
            ->test(TicketsComponent::class)
            ->set('conformityTicketId', $ticket->id)
            ->call('saveUserConformity');

        $this->assertEquals('RESUELTO', $ticket->fresh()->status);
        $this->assertNull($ticket->fresh()->user_conformity);

        // Invitado o usuario no autenticado en NewTicket -> Bloqueado
        Livewire::test(NewTicketComponent::class)
            ->set('officeName', 'OFICINA DE REGIDORES')
            ->call('openConformityModal', $ticket->id)
            ->assertSet('isConformityModalOpen', false);
    }

    public function test_visto_bueno_can_be_granted_by_technician_with_delegated_authority(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-CONF-AUTH-3',
            'applicant_name' => 'Usuario Prueba',
            'office_name' => 'OFICINA DE REGIDORES',
            'category' => 'SOFTWARE',
            'priority' => 'MEDIA',
            'subject' => 'Prueba con técnico delegado con permiso',
            'description' => 'Descripción de prueba',
            'status' => 'RESUELTO',
        ]);

        $tech = User::where('username', 'soporte1')->first();
        // Asignar autoridad delegada
        $tech->allowed_modules = array_merge((array) ($tech->allowed_modules ?? []), ['helpdesk.tickets.approve']);
        $tech->save();

        $this->assertTrue($tech->fresh()->canApproveTicketConformity());

        // Ahora el técnico con autoridad delegada SÍ puede otorgar el visto bueno
        Livewire::actingAs($tech)
            ->test(TicketsComponent::class)
            ->call('openConformityModal', $ticket->id)
            ->assertSet('isConformityModalOpen', true)
            ->call('setRating', 5)
            ->call('setConformity', true)
            ->call('saveUserConformity')
            ->assertSet('isConformityModalOpen', false);

        $this->assertEquals('CERRADO', $ticket->fresh()->status);
        $this->assertTrue((bool) $ticket->fresh()->user_conformity);
    }
    public function test_applicant_user_can_grant_visto_bueno_to_their_own_resolved_ticket(): void
    {
        $reportante = User::where('role', 'like', '%Reportante%')->first();
        if (! $reportante) {
            $reportante = User::create([
                'username' => 'usuario_reportante_test',
                'full_name' => 'MARIA DEL CARMEN FLORES',
                'email' => 'mflores@castilla.gob.pe',
                'password' => bcrypt('password'),
                'role' => 'Personal General / Reportante',
                'allowed_modules' => ['helpdesk'],
                'is_active' => true,
            ]);
        }

        $myTicket = Ticket::create([
            'ticket_number' => 'TCK-MY-RESOLVED-1',
            'applicant_name' => $reportante->full_name,
            'applicant_email' => $reportante->email,
            'office_name' => 'OFICINA DE REGIDORES',
            'category' => 'HARDWARE',
            'priority' => 'MEDIA',
            'subject' => 'Mi impresora ya fue reparada',
            'description' => 'El técnico ya limpió los rodillos',
            'status' => 'RESUELTO',
        ]);

        $this->assertTrue($myTicket->isApplicantUser($reportante));
        $this->assertTrue($myTicket->canUserGiveConformity($reportante));

        // El usuario reportante ve la alerta preventiva de tickets pendientes de visto bueno
        // y PUEDE otorgar visto bueno a su propio ticket
        Livewire::actingAs($reportante)
            ->test(NewTicketComponent::class)
            ->set('officeName', 'OFICINA DE REGIDORES')
            ->assertSee('pendiente(s) de su Visto Bueno')
            ->assertSee($myTicket->ticket_number)
            ->call('openConformityModal', $myTicket->id)
            ->assertSet('isConformityModalOpen', true)
            ->call('setRating', 5)
            ->call('setConformity', true)
            ->call('saveUserConformity')
            ->assertSet('isConformityModalOpen', false);

        $fresh = $myTicket->fresh();
        $this->assertEquals('CERRADO', $fresh->status);
        $this->assertTrue((bool) $fresh->user_conformity);
    }

    public function test_applicant_cannot_grant_visto_bueno_to_another_users_ticket(): void
    {
        $reportante1 = User::create([
            'username' => 'reportante_user_1',
            'full_name' => 'JUAN CARLOS PEREZ LOPEZ',
            'email' => 'jperez@castilla.gob.pe',
            'password' => bcrypt('password'),
            'role' => 'Personal General / Reportante',
            'allowed_modules' => ['helpdesk'],
            'is_active' => true,
        ]);

        $otherTicket = Ticket::create([
            'ticket_number' => 'TCK-OTHER-USER-1',
            'applicant_name' => 'OTRO USUARIO DISTINTO',
            'applicant_email' => 'otro@castilla.gob.pe',
            'office_name' => 'OFICINA DE REGIDORES',
            'category' => 'SOFTWARE',
            'priority' => 'BAJA',
            'subject' => 'Ticket de otro usuario',
            'description' => 'Descripción de prueba',
            'status' => 'RESUELTO',
        ]);

        $this->assertFalse($otherTicket->isApplicantUser($reportante1));
        $this->assertFalse($otherTicket->canUserGiveConformity($reportante1));

        // Un usuario reportante NO puede otorgar visto bueno a un ticket ajeno
        Livewire::actingAs($reportante1)
            ->test(NewTicketComponent::class)
            ->set('officeName', 'OFICINA DE REGIDORES')
            ->call('openConformityModal', $otherTicket->id)
            ->assertSet('isConformityModalOpen', false);
    }
}
