<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Helpdesk\KnowledgeArticle;
use App\Models\Helpdesk\Ticket;
use App\Models\Helpdesk\TicketAuditLog;
use App\Models\Helpdesk\TicketLoan;
use App\Models\Helpdesk\TicketSupply;
use App\Models\Helpdesk\TicketTechnicalDetail;
use App\Models\Itam\Asset;
use App\Models\Itam\Supply;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HelpdeskSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSupplies();
        $this->seedKnowledgeArticles();
        $this->seedTickets();
    }

    private function seedSupplies(): void
    {
        $supplies = [
            [
                'code' => 'INS-SSD-001',
                'name' => 'Disco Estado Sólido Kingston NV2 512GB NVMe M.2',
                'category' => 'REPUESTOS_HARDWARE',
                'stock' => 1,
                'min_stock' => 2,
                'unit' => 'UNIDAD',
                'unit_cost' => 165.00,
                'location' => 'Almacén ODT - Gabinete Repuestos',
                'compatible_models' => 'PCs y Laptops con zócalo M.2 NVMe',
                'notes' => 'Stock crítico: solo 1 unidad restante.',
            ],
            [
                'code' => 'INS-RAM-001',
                'name' => 'Memoria RAM Kingston Fury Beast 16GB DDR4 3200MHz',
                'category' => 'REPUESTOS_HARDWARE',
                'stock' => 0,
                'min_stock' => 2,
                'unit' => 'UNIDAD',
                'unit_cost' => 155.00,
                'location' => 'Almacén ODT - Gabinete Repuestos',
                'compatible_models' => 'Computadoras OptiPlex y ProDesk',
                'notes' => 'Stock agotado. Requiere adquisición urgente.',
            ],
            [
                'code' => 'INS-LIM-001',
                'name' => 'Alcohol Isopropílico 99.9% 1 Litro + Paños Microfibra',
                'category' => 'LIMPIEZA',
                'stock' => 6,
                'min_stock' => 2,
                'unit' => 'UNIDAD',
                'unit_cost' => 28.50,
                'location' => 'Almacén ODT - Gabinete Químicos',
                'compatible_models' => 'Mantenimiento preventivo general',
                'notes' => 'Solvente dieléctrico para limpieza de placas y rodillos.',
            ],
        ];

        foreach ($supplies as $s) {
            Supply::updateOrCreate(['code' => $s['code']], $s);
        }
    }

    private function seedKnowledgeArticles(): void
    {
        $rootUser = User::where('username', 'root')->first();

        $articles = [
            [
                'title' => 'Desatasco de papel y limpieza de rodillos en Impresoras Epson EcoTank',
                'category' => 'HARDWARE',
                'summary' => 'Guía rápida para solucionar error de atasco de papel o arrastre múltiple en bandejas ADF y frontal.',
                'symptoms' => 'La impresora emite alerta lumínica de papel atascado o no jala la hoja de oficio.',
                'solution_steps' => "1. Apagar el equipo y desconectar el cable de poder.\n2. Abrir la compuerta trasera y retirar cualquier resto de papel trabado jalando suavemente con ambas manos.\n3. Humedecer un paño con alcohol isopropílico y limpiar los rodillos de alimentación.\n4. Reconectar, encender y realizar prueba de impresión.",
                'tags' => ['Impresoras', 'EcoTank', 'Atasco', 'Hardware'],
                'author_technician_id' => $rootUser?->id,
                'author_technician_name' => 'Ing. Carlos Mendoza (Soporte TI)',
                'views_count' => 42,
                'helpful_count' => 18,
            ],
            [
                'title' => 'Solución a caída de conectividad de red local (IP y DNS municipal)',
                'category' => 'RED_INTERNET',
                'summary' => 'Pasos para restablecer conexión de red cuando el ícono de red muestra "Sin acceso a Internet".',
                'symptoms' => 'No se puede acceder al SIGM, ni a internet ni a carpetas compartidas de la municipalidad.',
                'solution_steps' => "1. Verificar conexión física del conector RJ-45 (LED verde/ámbar parpadeando).\n2. Abrir consola cmd como Administrador y ejecutar:\n   ipconfig /release\n   ipconfig /renew\n   ipconfig /flushdns\n3. Si persiste el error, verificar que la tarjeta esté configurada en DHCP o en el rango 192.168.1.X.",
                'tags' => ['Redes', 'DHCP', 'Conectividad', 'Ethernet'],
                'author_technician_id' => $rootUser?->id,
                'author_technician_name' => 'Téc. Roberto Valdiviezo',
                'views_count' => 85,
                'helpful_count' => 34,
            ],
            [
                'title' => 'Diagnóstico de reinicios continuos o Pantalla Azul (BSOD) en PCs de Oficina',
                'category' => 'HARDWARE',
                'summary' => 'Procedimiento de descarte de memorias RAM sucias y sobrecalentamiento de procesador.',
                'symptoms' => 'La computadora se reinicia inesperadamente al cabo de unos minutos de uso o muestra pantalla azul.',
                'solution_steps' => "1. Desconectar de la corriente y abrir la tapa lateral del gabinete.\n2. Retirar los módulos de memoria RAM.\n3. Limpiar los contactos dorados con borrador blanco y brocha antiestática.\n4. Comprobar ventilador de procesador; aplicar pasta térmica si es necesario.\n5. Volver a colocar la RAM y probar.",
                'tags' => ['BSOD', 'RAM', 'Mantenimiento', 'PC'],
                'author_technician_id' => $rootUser?->id,
                'author_technician_name' => 'Ing. Carlos Mendoza (Soporte TI)',
                'views_count' => 64,
                'helpful_count' => 22,
            ],
            [
                'title' => 'Asignación de permisos de acceso a carpetas compartidas departamentales',
                'category' => 'PERMISOS',
                'summary' => 'Flujo para dar acceso de lectura/escritura a las carpetas en el servidor de archivos municipal.',
                'symptoms' => 'El usuario recibe el mensaje "Acceso denegado. No tiene permisos para acceder a \\\\SERVER_MDC\\Documentos".',
                'solution_steps' => "1. Validar autorización del Jefe de Área o Gerente mediante correo o papeleta.\n2. En el servidor Active Directory, agregar al usuario al grupo de seguridad correspondiente.\n3. En el cliente, cerrar sesión de Windows y volver a iniciar para refrescar credenciales.\n4. Mapear la unidad de red: net use Z: \\\\SERVER_MDC\\Fiscalizacion /persistent:yes.",
                'tags' => ['Permisos', 'Red', 'ActiveDirectory', 'Carpetas'],
                'author_technician_id' => $rootUser?->id,
                'author_technician_name' => 'Téc. Roberto Valdiviezo',
                'views_count' => 30,
                'helpful_count' => 15,
            ],
        ];

        foreach ($articles as $a) {
            KnowledgeArticle::updateOrCreate(['title' => $a['title']], $a);
        }
    }

    private function seedTickets(): void
    {
        $techUser = User::where('username', 'jperez')->first() ?? User::where('username', 'root')->first();
        $techId = $techUser?->id;
        $techName = $techUser?->name ?? 'Ing. Carlos Mendoza (Soporte TI)';

        // Buscar activos para asociar
        $prnAsset = Asset::where('computer_code', 'MDC-TI-PRN-0001')->first();
        $monAsset = Asset::where('computer_code', 'MDC-TI-MON-0001')->first();
        $pcAsset = Asset::where('computer_code', 'MDC-TI-PC-0001')->first();
        $lapAsset = Asset::where('computer_code', 'MDC-TI-LAP-0001')->first();
        $estAsset = Asset::where('computer_code', 'MDC-TI-EST-0012')->first();

        // Buscar personas para solicitantes según nómina oficial municipal
        $person1 = Person::find(9);  // Luis Ricardo Llacahuanga Timoteo (GSP)
        $person2 = Person::find(11); // Jack Terreros Garay (SRCPPS)
        $person3 = Person::find(12); // Karen Sthefany Febre Zegarra (OMAPED)
        $person4 = Person::find(10); // Doris Lopez Garcia (ULE)
        $person5 = Person::find(5);  // Gustavo Adolfo Paredes Shimokawa (STTV)
        $personMaza = Person::where('document_number', '42712478')->first() ?? Person::find(43); // Cesar Ivan Maza (OGDAC)
        $personRegidores = Person::where('document_number', '41461309')->first() ?? Person::find(40); // Guillermo Rodriguez (OR)

        // Insumos
        $conSupply = Supply::where('code', 'INS-CON-003')->first();
        $tnrSupply = Supply::where('code', 'INS-TNR-001')->first();

        // -------------------------------------------------------------
        // Ticket 1: CERRADO con Visto Bueno y Calificación 5 estrellas
        // -------------------------------------------------------------
        $t1 = Ticket::updateOrCreate(
            ['ticket_number' => 'TK-2026-00001'],
            [
                'applicant_id' => $person1?->id,
                'applicant_name' => $person1?->full_name ?? 'Luis Ricardo Llacahuanga Timoteo',
                'applicant_phone' => $person1?->phone ?? 'Anexo 115',
                'applicant_email' => $person1?->email ?? 'usuario@castilla.gob.pe',
                'office_name' => $person1?->office ?? 'Gerencia de Servicios Públicos',
                'asset_id' => $prnAsset?->id,
                'asset_computer_code' => $prnAsset?->computer_code ?? 'MDC-TI-PRN-0001',
                'asset_category' => 'Impresora y Multifuncional',
                'category' => 'HARDWARE',
                'priority' => 'MEDIA',
                'subject' => 'Impresora Epson se atasca constantemente al imprimir resoluciones',
                'description' => 'Desde ayer en la tarde las hojas membretadas gruesas de mesa de partes no ingresan y se arrugan en el alimentador superior trabando la cola.',
                'status' => 'CERRADO',
                'assigned_technician_id' => $techId,
                'assigned_technician_name' => $techName,
                'assigned_technician_phone' => 'Anexo 104',
                'started_at' => Carbon::now()->subDays(3),
                'resolved_at' => Carbon::now()->subDays(2),
                'closed_at' => Carbon::now()->subDays(2)->addHour(),
                'user_conformity' => true,
                'user_rating' => 5,
                'user_feedback' => 'Excelente atención, el técnico limpió los rodillos de arrastre y ahora jala las hojas con normalidad. Muy rápido y puntual.',
                'is_locked' => false,
            ]
        );

        TicketTechnicalDetail::updateOrCreate(
            ['ticket_id' => $t1->id],
            [
                'confirmed_category' => 'HARDWARE',
                'real_diagnosis' => 'Acumulación de polvo y residuo de tóner en rodillos de goma de arrastre primario (pickup roller).',
                'solution_applied' => 'Limpieza profunda con solución desengrasante isopropílica y calibración de guías laterales de bandeja.',
                'is_definitive_decommission' => false,
            ]
        );

        TicketAuditLog::updateOrCreate(
            ['ticket_id' => $t1->id, 'action' => 'CIERRE_CONFORMIDAD'],
            [
                'previous_status' => 'RESUELTO',
                'new_status' => 'CERRADO',
                'performed_by_user_id' => $techId,
                'performed_by_user_name' => $t1->applicant_name,
                'notes' => 'Usuario emitió conformidad con calificación 5 estrellas.',
                'timestamp' => Carbon::now()->subDays(2)->addHour(),
            ]
        );

        // -------------------------------------------------------------
        // Ticket 2: EN_LABORATORIO con Préstamo Provisional (RF-10)
        // -------------------------------------------------------------
        $t2 = Ticket::updateOrCreate(
            ['ticket_number' => 'TK-2026-00002'],
            [
                'applicant_id' => $person2?->id,
                'applicant_name' => $person2?->full_name ?? 'Jack Terreros Garay',
                'applicant_phone' => $person2?->phone ?? 'Anexo 111',
                'applicant_email' => $person2?->email ?? 'usuario@castilla.gob.pe',
                'office_name' => $person2?->office ?? 'Subgerencia de Registro Civil, Población y Salud',
                'asset_id' => $monAsset?->id,
                'asset_computer_code' => $monAsset?->computer_code ?? 'MDC-TI-MON-0001',
                'asset_category' => 'Monitor de Video',
                'category' => 'HARDWARE',
                'priority' => 'ALTA',
                'subject' => 'Monitor de videovigilancia parpadea y muestra líneas horizontales negras',
                'description' => 'La pantalla del puesto de monitoreo de cámaras de serenazgo se apaga intermitentemente cada 2 minutos impidiendo el seguimiento.',
                'status' => 'EN_LABORATORIO',
                'assigned_technician_id' => $techId,
                'assigned_technician_name' => 'Téc. Roberto Valdiviezo',
                'assigned_technician_phone' => 'Anexo 104',
                'started_at' => Carbon::now()->subDay(),
                'is_locked' => true,
                'locked_by' => 'Téc. Roberto Valdiviezo',
            ]
        );

        if ($lapAsset && $monAsset) {
            TicketLoan::updateOrCreate(
                ['ticket_id' => $t2->id],
                [
                    'temporary_asset_id' => $lapAsset->id,
                    'temporary_asset_code' => $lapAsset->computer_code,
                    'temporary_asset_name' => 'Lenovo ThinkPad E14 (Laptop de Contingencia)',
                    'damaged_asset_id' => $monAsset->id,
                    'damaged_asset_code' => $monAsset->computer_code,
                    'status' => 'PRESTADO',
                    'loan_date' => Carbon::now()->subDay()->format('Y-m-d H:i'),
                    'notes' => 'Se entregó laptop de contingencia para no paralizar el monitoreo de serenazgo mientras se repara en laboratorio.',
                ]
            );
        }

        TicketTechnicalDetail::updateOrCreate(
            ['ticket_id' => $t2->id],
            [
                'confirmed_category' => 'HARDWARE',
                'real_diagnosis' => 'Condensadores electrolíticos hinchados en la etapa secundaria de la fuente interna.',
                'solution_applied' => 'En proceso de desoldado y reemplazo de capacitores en banco de pruebas ODT.',
                'is_definitive_decommission' => false,
            ]
        );

        // -------------------------------------------------------------
        // Ticket 3: EN_ATENCION con Insumo Descargado de Almacén (RF-09)
        // -------------------------------------------------------------
        $t3 = Ticket::updateOrCreate(
            ['ticket_number' => 'TK-2026-00003'],
            [
                'applicant_id' => $person3?->id,
                'applicant_name' => $person3?->full_name ?? 'Karen Sthefany Febre Zegarra',
                'applicant_phone' => $person3?->phone ?? 'Anexo 112',
                'applicant_email' => $person3?->email ?? 'usuario@castilla.gob.pe',
                'office_name' => $person3?->office ?? 'Oficina Municipal de Atención a la Persona con Discapacidad',
                'asset_id' => $pcAsset?->id,
                'asset_computer_code' => $pcAsset?->computer_code ?? 'MDC-TI-PC-0001',
                'asset_category' => 'PC de Escritorio',
                'category' => 'RED_INTERNET',
                'priority' => 'CRITICA',
                'subject' => 'Sin acceso a la red de Gerencia Municipal tras remodelación de oficina',
                'description' => 'El conector que viene de la canaleta de red se rompió en la pestaña al mover el escritorio y no engancha.',
                'status' => 'EN_ATENCION',
                'assigned_technician_id' => $techId,
                'assigned_technician_name' => $techName,
                'assigned_technician_phone' => 'Anexo 104',
                'started_at' => Carbon::now()->subHours(2),
                'is_locked' => true,
                'locked_by' => $techName,
            ]
        );

        if ($conSupply) {
            TicketSupply::updateOrCreate(
                ['ticket_id' => $t3->id, 'supply_id' => $conSupply->id],
                [
                    'supply_code' => $conSupply->code,
                    'supply_name' => $conSupply->name,
                    'quantity' => 2,
                    'unit' => 'UNIDAD',
                    'unit_cost' => 0.45,
                    'notes' => 'Instalación y crimpeado de conector RJ45 blindado bajo norma ANSI/TIA-568-B.',
                ]
            );
        }

        TicketAuditLog::updateOrCreate(
            ['ticket_id' => $t3->id, 'action' => 'EN_ATENCION'],
            [
                'previous_status' => 'ABIERTO',
                'new_status' => 'EN_ATENCION',
                'performed_by_user_id' => $techId,
                'performed_by_user_name' => $techName,
                'notes' => 'Técnico presente en Gerencia Municipal para crimpeado de cable de red.',
                'timestamp' => Carbon::now()->subHours(1),
            ]
        );

        // -------------------------------------------------------------
        // Ticket 4: ABIERTO (En cola)
        // -------------------------------------------------------------
        Ticket::updateOrCreate(
            ['ticket_number' => 'TK-2026-00004'],
            [
                'applicant_id' => $person4?->id,
                'applicant_name' => $person4?->full_name ?? 'Doris Lopez Garcia',
                'applicant_phone' => $person4?->phone ?? 'Anexo 110',
                'applicant_email' => $person4?->email ?? 'usuario@castilla.gob.pe',
                'office_name' => $person4?->office ?? 'Oficina de la Unidad Local de Empadronamiento',
                'category' => 'PERMISOS',
                'priority' => 'MEDIA',
                'subject' => 'Solicitud de acceso a carpeta compartida de Resoluciones Sancionadoras 2026',
                'description' => 'Requiero acceso de lectura y escritura a la ruta de red compartida para archivar actas de inspección nocturna.',
                'status' => 'ABIERTO',
                'is_locked' => false,
            ]
        );

        // -------------------------------------------------------------
        // Ticket 5: EN_PAUSA (Esperando repuesto o proveedor)
        // -------------------------------------------------------------
        $t5 = Ticket::updateOrCreate(
            ['ticket_number' => 'TK-2026-00005'],
            [
                'applicant_id' => $person5?->id,
                'applicant_name' => $person5?->full_name ?? 'Gustavo Adolfo Paredes Shimokawa',
                'applicant_phone' => $person5?->phone ?? 'Anexo 105',
                'applicant_email' => $person5?->email ?? 'usuario@castilla.gob.pe',
                'office_name' => $person5?->office ?? 'Subgerencia de Transporte, Tránsito y Vialidad',
                'asset_id' => $estAsset?->id,
                'asset_computer_code' => $estAsset?->computer_code ?? 'MDC-TI-EST-0012',
                'asset_category' => 'Estabilizador de Voltaje',
                'category' => 'HARDWARE',
                'priority' => 'ALTA',
                'subject' => 'Estabilizador emitió humo y chispas con olor a quemado',
                'description' => 'Durante la tormenta eléctrica el estabilizador sufrió una sobrecarga y ya no entrega energía al plotter.',
                'status' => 'EN_PAUSA',
                'assigned_technician_id' => $techId,
                'assigned_technician_name' => 'Téc. Roberto Valdiviezo',
                'assigned_technician_phone' => 'Anexo 104',
                'started_at' => Carbon::now()->subDay(),
                'is_locked' => false,
            ]
        );

        TicketTechnicalDetail::updateOrCreate(
            ['ticket_id' => $t5->id],
            [
                'confirmed_category' => 'HARDWARE',
                'real_diagnosis' => 'Transformador toroidal y varistores de supresión de picos completamente calcinados.',
                'pause_reason' => 'En espera de cotización formal de repuesto de transformador de proveedor autorizado.',
                'is_definitive_decommission' => false,
            ]
        );

        // -------------------------------------------------------------
        // Ticket 6: RESUELTO con Baja Técnica Generada (RF-11)
        // -------------------------------------------------------------
        $t6 = Ticket::updateOrCreate(
            ['ticket_number' => 'TK-2026-00006'],
            [
                'applicant_id' => $personMaza?->id,
                'applicant_name' => $personMaza?->full_name ?? 'Cesar Ivan Maza Seminario',
                'applicant_phone' => '968741258',
                'applicant_email' => 'cmaza@castilla.gob.pe',
                'office_name' => 'Oficina de Gestión Documentaria y Atención al Ciudadano',
                'asset_computer_code' => 'MDC-TI-PC-0004',
                'asset_category' => 'PC de Escritorio',
                'category' => 'HARDWARE',
                'priority' => 'MEDIA',
                'subject' => 'CPU antiguo de módulo 3 emite humo y olor a quemado',
                'description' => 'El equipo no soportó el encendido matutino y se apagó con olor a quemado.',
                'status' => 'RESUELTO',
                'assigned_technician_id' => $techId,
                'assigned_technician_name' => $techName,
                'assigned_technician_phone' => 'Anexo 104',
                'started_at' => Carbon::now()->subDays(2),
                'resolved_at' => Carbon::now()->subDay(),
                'is_locked' => false,
            ]
        );

        TicketTechnicalDetail::updateOrCreate(
            ['ticket_id' => $t6->id],
            [
                'confirmed_category' => 'HARDWARE',
                'real_diagnosis' => 'Cortocircuito en VRM de placa madre y fuente de poder calcinada.',
                'solution_applied' => 'Equipo tecnológicamente obsoleto sin repuestos en mercado. Se declara inoperativo.',
                'is_definitive_decommission' => true,
                'decommission_act_number' => 'ACTA-BAJA-2026-0001',
                'decommission_reason' => 'Daño irreversible por descarga eléctrica y costo de reparación no rentable.',
            ]
        );

        // -------------------------------------------------------------
        // Tickets 7, 8, 9: OFICINA DE REGIDORES (Para seguimiento y visto bueno)
        // -------------------------------------------------------------
        Ticket::updateOrCreate(
            ['ticket_number' => 'INC-2026-003'],
            [
                'applicant_id' => $personRegidores?->id,
                'applicant_name' => $personRegidores?->full_name ?? 'JULIO ERNESTO CHAVEZ CRISANTO',
                'applicant_phone' => 'Anexo 104',
                'applicant_email' => 'admin@castilla.gob.pe',
                'office_name' => 'OFICINA DE REGIDORES',
                'category' => 'HARDWARE',
                'priority' => 'MEDIA',
                'subject' => 'Impresora no enciende en caja',
                'description' => 'La impresora multifuncional de la oficina de regidores no enciende tras corte de luz.',
                'status' => 'EN_ATENCION',
                'assigned_technician_id' => $techId,
                'assigned_technician_name' => 'Téc. Roberto Valdiviezo',
                'assigned_technician_phone' => 'Anexo 104',
                'started_at' => Carbon::now()->subHours(2),
            ]
        );

        Ticket::updateOrCreate(
            ['ticket_number' => 'INC-2026-002'],
            [
                'applicant_id' => $personRegidores?->id,
                'applicant_name' => $personRegidores?->full_name ?? 'JULIO ERNESTO CHAVEZ CRISANTO',
                'applicant_phone' => 'Anexo 104',
                'applicant_email' => 'admin@castilla.gob.pe',
                'office_name' => 'OFICINA DE REGIDORES',
                'category' => 'SOFTWARE',
                'priority' => 'BAJA',
                'subject' => 'Actualización de aplicativo SIGM en terminal',
                'description' => 'Se completó la actualización de versión del aplicativo SIGM en terminal de consulta.',
                'status' => 'RESUELTO',
                'assigned_technician_id' => $techId,
                'assigned_technician_name' => $techName,
                'assigned_technician_phone' => 'Anexo 104',
                'started_at' => Carbon::now()->subDays(1),
                'resolved_at' => Carbon::now()->subHours(3),
            ]
        );

        Ticket::updateOrCreate(
            ['ticket_number' => 'INC-2026-001'],
            [
                'applicant_id' => $personRegidores?->id,
                'applicant_name' => $personRegidores?->full_name ?? 'JULIO ERNESTO CHAVEZ CRISANTO',
                'applicant_phone' => 'Anexo 104',
                'applicant_email' => 'admin@castilla.gob.pe',
                'office_name' => 'OFICINA DE REGIDORES',
                'category' => 'RED_INTERNET',
                'priority' => 'MEDIA',
                'subject' => 'Reconexión de cable de red punto 14',
                'description' => 'Reemplazo de conector RJ45 y prueba de enlace al rack del segundo piso.',
                'status' => 'RESUELTO',
                'assigned_technician_id' => $techId,
                'assigned_technician_name' => 'Téc. Juan Pérez',
                'assigned_technician_phone' => 'Anexo 104',
                'started_at' => Carbon::now()->subDays(3),
                'resolved_at' => Carbon::now()->subDays(2),
            ]
        );
    }
}
