<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Helpdesk\KnowledgeArticle;
use App\Models\Helpdesk\Ticket;
use App\Models\Helpdesk\TicketTechnicalDetail;
use App\Models\Office;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HelpdeskModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_creation_and_technical_detail(): void
    {
        $office = Office::create([
            'code' => '03.02.05',
            'acronym' => 'ODT',
            'name' => 'OFICINA DE DESARROLLO TECNOLÓGICO',
        ]);

        $person = Person::create([
            'type' => 'PERSONAL',
            'document_type' => 'DNI',
            'document_number' => '45821903',
            'first_name' => 'JUAN',
            'paternal_surname' => 'PEREZ',
        ]);

        $tech = User::create([
            'username' => 'tech1',
            'password' => Hash::make('secret'),
            'full_name' => 'Tecnico TI',
            'role' => 'Técnico de Soporte',
        ]);

        $ticket = Ticket::create([
            'ticket_number' => 'TCK-2026-0001',
            'applicant_id' => $person->id,
            'applicant_name' => 'Juan Perez',
            'office_id' => $office->id,
            'office_name' => 'ODT',
            'category' => 'HARDWARE',
            'priority' => 'ALTA',
            'subject' => 'PC no enciende',
            'description' => 'El equipo emite 3 pitidos al encender.',
            'status' => 'ABIERTO',
            'assigned_technician_id' => $tech->id,
            'assigned_technician_name' => 'Tecnico TI',
        ]);

        $detail = TicketTechnicalDetail::create([
            'ticket_id' => $ticket->id,
            'real_diagnosis' => 'Falla en módulo de memoria RAM',
            'solution_applied' => 'Limpieza y reajuste en Slot 1',
        ]);

        $this->assertEquals('TCK-2026-0001', $ticket->ticket_number);
        $this->assertEquals('Falla en módulo de memoria RAM', $ticket->technicalDetail->real_diagnosis);
        $this->assertEquals('ODT', $ticket->office->acronym);
    }

    public function test_knowledge_article(): void
    {
        $article = KnowledgeArticle::create([
            'title' => 'Solución atasco de papel',
            'category' => 'HARDWARE',
            'summary' => 'Limpieza de rodillos',
            'solution_steps' => '1. Desconectar. 2. Limpiar rodillos.',
            'tags' => ['impresoras', 'epson'],
            'author_technician_name' => 'Ing. Mendoza',
        ]);

        $this->assertIsArray($article->tags);
        $this->assertContains('impresoras', $article->tags);
        $this->assertEquals('Solución atasco de papel', $article->title);
    }
}
