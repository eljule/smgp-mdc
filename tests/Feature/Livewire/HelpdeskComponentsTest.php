<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\Helpdesk\Knowledge as KnowledgeComponent;
use App\Livewire\Helpdesk\NewTicket as NewTicketComponent;
use App\Livewire\Helpdesk\Tickets as TicketsComponent;
use App\Models\Helpdesk\KnowledgeArticle;
use App\Models\Helpdesk\Ticket;
use App\Models\Office;
use App\Models\Person;
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
        $response->assertStatus(200);
        $response->assertSee('Base de Conocimientos TI');

        if ($article) {
            $initialViews = $article->views_count;
            Livewire::test(KnowledgeComponent::class)
                ->assertSee($article->title)
                ->call('selectArticle', $article->id)
                ->assertSet('selectedArticleId', $article->id);

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

        $ticket = Ticket::where('office_name', 'like', '%REGIDORES%')->where('status', '!=', 'CERRADO')->first();
        $this->assertNotNull($ticket);

        Livewire::test(NewTicketComponent::class)
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
        $this->assertEquals('Téc. Juan Pérez', $fresh->assigned_technician_name);
    }
}
