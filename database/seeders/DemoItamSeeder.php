<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoItamSeeder extends Seeder
{
    public function run(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';
        if ($isPgsql) {
            DB::statement('SET session_replication_role = replica;');
        } else {
            Schema::disableForeignKeyConstraints();
        }

        // Asegurar seeders previos si se corre de forma aislada
        if (DB::table('core_offices')->count() === 0) {
            $this->call(OfficeSeeder::class);
        }
        if (DB::table('core_people')->count() === 0) {
            $this->call(PersonSeeder::class);
        }
        if (DB::table('core_users')->count() === 0) {
            $this->call(UserSeeder::class);
        }
        if (DB::table('itam_asset_categories')->count() === 0) {
            $this->call(ItamSeeder::class);
        }

        DB::table('itam_maintenance_supplies')->delete();
        DB::table('itam_maintenance_orders')->delete();
        DB::table('itam_audit_verifications')->delete();
        DB::table('itam_inventory_audits')->delete();
        DB::table('itam_asset_movements')->delete();
        DB::table('itam_asset_loan_items')->delete();
        DB::table('itam_asset_loans')->delete();
        DB::table('itam_asset_software')->delete();
        DB::table('itam_software')->delete();
        DB::table('itam_assets')->delete();

        // 1. Obtener identificadores y nombres reales de core_people y core_offices
        $odtBoss = DB::table('core_people')->where('document_number', '10783864')->first()
            ?? DB::table('core_people')->where('office', 'like', '%DESARROLLO TECNOL%')->first();
        $odtBossId = $odtBoss?->id ?? 35;
        $odtOffice = '[ODT] OFICINA DE DESARROLLO TECNOLÓGICO';

        $alcalde = DB::table('core_people')->where('office', 'like', '%ALCALD%')->first();
        $alcaldeId = $alcalde?->id ?? 46;
        $alcOffice = '[ALC] ALCALDÍA';

        $gatBoss = DB::table('core_people')->where('office', 'like', '%ADMINISTRACIÓN TRIBUTARIA%')->first();
        $gatBossId = $gatBoss?->id ?? 31;
        $gatOffice = '[GAT] GERENCIA DE ADMINISTRACIÓN TRIBUTARIA';

        $transporteBoss = DB::table('core_people')->where('office', 'like', '%TRANSPORTE%')->first();
        $transporteBossId = $transporteBoss?->id ?? 5;
        $transporteOffice = '[STTV] SUBGERENCIA DE TRANSPORTE, TRÁNSITO Y VIALIDAD';

        $gspBoss = DB::table('core_people')->where('office', 'like', '%SERVICIOS PÚBLICOS%')->first();
        $gspBossId = $gspBoss?->id ?? 9;
        $gspOffice = '[GSP] GERENCIA DE SERVICIOS PÚBLICOS';
        $gspBossName = $gspBoss ? trim($gspBoss->first_name . ' ' . $gspBoss->paternal_surname . ' ' . $gspBoss->maternal_surname) : 'LUIS RICARDO LLACSAHUANGA TIMOTEO';

        $almacenBoss = DB::table('core_people')->where('office', 'like', '%ALMACEN%')->first();
        $almacenBossId = $almacenBoss?->id ?? 38;
        $almacenOffice = '[OACG] OFICINA DE ALMACEN CENTRAL GENERAL';

        $planeamientoBoss = DB::table('core_people')->where('office', 'like', '%PLANEAMIENTO%')->first();
        $planeamientoBossId = $planeamientoBoss?->id ?? 33;
        $planeamientoOffice = '[OGPP] OFICINA GENERAL DE PLANEAMIENTO Y PRESUPUESTO';

        $techUser = DB::table('core_users')->where('username', 'soporte1')->first();
        $techName = $techUser?->full_name ?? 'JAIME HENRY SANDOVAL NIEVES';

        $assets = [
            [
                'id' => 1,
                'computer_code' => 'MDC-TI-PC-0001',
                'patrimonial_code' => '7408770001',
                'serial_number' => '4CE1234XYZ',
                'category_id' => 1,
                'brand_id' => 3,
                'model_id' => 8,
                'parent_asset_id' => null,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => $odtOffice,
                'assigned_person_id' => $odtBossId,
                'acquisition_date' => '2023-01-15',
                'supplier' => 'CORPORACIÓN TECNOLÓGICA DEL NORTE S.A.C.',
                'warranty_end_date' => '2026-01-15',
                'is_loanable' => false,
                'specifications' => '{"os": "Windows 11 Pro 64-bit", "ram_gb": "16 GB", "processor": "Intel Core i7-12700 2.10GHz", "storage_primary": "512 GB SSD NVMe M.2", "storage_secondary": "1 TB HDD SATA"}',
                'notes' => 'Estación principal de administración de servidores ODT',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'computer_code' => 'MDC-TI-MON-0001',
                'patrimonial_code' => '7408770002',
                'serial_number' => 'CN-0ABC12-74261',
                'category_id' => 3,
                'brand_id' => 8,
                'model_id' => 22,
                'parent_asset_id' => 1,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => $odtOffice,
                'assigned_person_id' => $odtBossId,
                'acquisition_date' => null,
                'supplier' => null,
                'warranty_end_date' => null,
                'is_loanable' => false,
                'specifications' => '{"panel_type": "IPS", "resolution": "1920x1080 (FHD)", "video_ports": "HDMI, DisplayPort, VGA", "screen_size_inches": 24}',
                'notes' => 'Monitor vinculado al puesto de administración ODT',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 3,
                'computer_code' => 'MDC-TI-TEC-0001',
                'patrimonial_code' => '7408770003',
                'serial_number' => '2145SC9812',
                'category_id' => 5,
                'brand_id' => 12,
                'model_id' => 46,
                'parent_asset_id' => 1,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => $odtOffice,
                'assigned_person_id' => $odtBossId,
                'acquisition_date' => null,
                'supplier' => null,
                'warranty_end_date' => null,
                'is_loanable' => false,
                'specifications' => '{"layout": "Español (Latinoamérica)", "connector_type": "USB Cableado"}',
                'notes' => 'Teclado vinculado a MDC-TI-PC-0001',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 4,
                'computer_code' => 'MDC-TI-MOU-0001',
                'patrimonial_code' => '7408770004',
                'serial_number' => '1934LZ0012',
                'category_id' => 6,
                'brand_id' => 12,
                'model_id' => 45,
                'parent_asset_id' => 1,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => $odtOffice,
                'assigned_person_id' => $odtBossId,
                'acquisition_date' => null,
                'supplier' => null,
                'warranty_end_date' => null,
                'is_loanable' => false,
                'specifications' => '{"dpi": "1000 DPI", "sensor_type": "Óptico LED", "connector_type": "USB Cableado"}',
                'notes' => 'Mouse óptico vinculado a MDC-TI-PC-0001',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 5,
                'computer_code' => 'MDC-TI-EST-0012',
                'patrimonial_code' => '7408770005',
                'serial_number' => 'FZ1202-99881',
                'category_id' => 7,
                'brand_id' => 6,
                'model_id' => 17,
                'parent_asset_id' => 1,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => $odtOffice,
                'assigned_person_id' => $odtBossId,
                'acquisition_date' => null,
                'supplier' => null,
                'warranty_end_date' => null,
                'is_loanable' => false,
                'specifications' => '{"voltage": "220V", "capacity_va": "1200VA / 600W", "outlets_count": 8}',
                'notes' => 'Protección eléctrica de estación ODT',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 6,
                'computer_code' => 'MDC-TI-LAP-0001',
                'patrimonial_code' => '7408770010',
                'serial_number' => 'PF-389102',
                'category_id' => 2,
                'brand_id' => 8,
                'model_id' => 34,
                'parent_asset_id' => null,
                'color' => 'Negro Grafito',
                'status' => 'EN_PRESTAMO',
                'physical_condition' => 'BUENO',
                'office' => $alcOffice,
                'assigned_person_id' => $alcaldeId,
                'acquisition_date' => '2023-04-10',
                'supplier' => 'LENOVO PERÚ S.A.',
                'warranty_end_date' => '2026-04-10',
                'is_loanable' => true,
                'specifications' => '{"os": "Windows 11 Pro 64-bit", "ram_gb": "16 GB", "storage": "512 GB SSD M.2 PCIe", "processor": "AMD Ryzen 5 PRO 5675U 2.3GHz", "has_charger": true, "screen_size": "14.0 Pulgadas"}',
                'notes' => 'Equipo para contingencia institucional y comisiones de regidores',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 7,
                'computer_code' => 'MDC-TI-PRN-0001',
                'patrimonial_code' => '7408770020',
                'serial_number' => 'X9K8712390',
                'category_id' => 4,
                'brand_id' => 5,
                'model_id' => 12,
                'parent_asset_id' => null,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => $gatOffice,
                'assigned_person_id' => $gatBossId,
                'acquisition_date' => '2023-02-20',
                'supplier' => 'EPSON DEL PERÚ S.A.',
                'warranty_end_date' => '2025-02-20',
                'is_loanable' => false,
                'specifications' => '{"network_ip": "192.168.1.120", "print_type": "Sistema Continuo de Tinta (EcoTank)", "connectivity": "Wi-Fi + USB"}',
                'notes' => 'Impresión de liquidaciones y formularios tributarios',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 8,
                'computer_code' => 'MDC-TI-PRN-0002',
                'patrimonial_code' => '7408770021',
                'serial_number' => 'VNB3400192',
                'category_id' => 4,
                'brand_id' => 8,
                'model_id' => 33,
                'parent_asset_id' => null,
                'color' => 'Blanco / Gris',
                'status' => 'EN_MANTENIMIENTO',
                'physical_condition' => 'REGULAR',
                'office' => $odtOffice,
                'assigned_person_id' => $odtBossId,
                'acquisition_date' => '2022-09-14',
                'supplier' => 'HP DIRECTO PERÚ',
                'warranty_end_date' => null,
                'is_loanable' => false,
                'specifications' => '{"network_ip": "192.168.1.155", "print_type": "Láser Monocromático (B/N)", "connectivity": "Ethernet + Wi-Fi + USB"}',
                'notes' => 'Atasco recurrente de hojas en bandeja 2. En taller ODT para cambio de rodillos.',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 9,
                'computer_code' => 'MDC-TI-SWT-0001',
                'patrimonial_code' => '7408770030',
                'serial_number' => '2194870021',
                'category_id' => 8,
                'brand_id' => 16,
                'model_id' => 53,
                'parent_asset_id' => null,
                'color' => 'Gris Oscuro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => $odtOffice,
                'assigned_person_id' => $odtBossId,
                'acquisition_date' => '2021-11-05',
                'supplier' => null,
                'warranty_end_date' => null,
                'is_loanable' => false,
                'specifications' => '{"speed": "Fast Ethernet (10/100 Mbps)", "poe_support": false, "ports_count": "24 Puertos"}',
                'notes' => 'Switch de distribución para estaciones de gerencia municipal',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 10,
                'computer_code' => 'MDC-TI-PC-0002',
                'patrimonial_code' => null,
                'serial_number' => 'S/N-PROVISIONAL',
                'category_id' => 1,
                'brand_id' => 7,
                'model_id' => 19,
                'parent_asset_id' => null,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'REGULAR',
                'office' => $transporteOffice,
                'assigned_person_id' => $transporteBossId,
                'acquisition_date' => '2020-05-12',
                'supplier' => null,
                'warranty_end_date' => null,
                'is_loanable' => false,
                'specifications' => '{"os": "Windows 10 Pro 64-bit", "ram_gb": "8 GB", "processor": "Intel Core i5-4460 3.20GHz", "storage_primary": "240 GB SSD SATA"}',
                'notes' => 'Equipo clon ensamblado. Requiere regularización de código SBN patrimonial. ⚠️ VERIFICACIÓN PENDIENTE',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];

        $parents = [];
        $rowsToInsert = [];
        foreach ($assets as $asset) {
            $parents[$asset['id']] = $asset['parent_asset_id'];
            $asset['parent_asset_id'] = null;
            $rowsToInsert[] = $asset;
        }

        foreach (array_chunk($rowsToInsert, 50) as $chunk) {
            DB::table('itam_assets')->insert($chunk);
        }

        foreach ($parents as $id => $parentId) {
            if ($parentId !== null) {
                DB::table('itam_assets')->where('id', $id)->update(['parent_asset_id' => $parentId]);
            }
        }

        $software = [
            [
                'id' => 1,
                'name' => 'Microsoft Windows 11 Professional',
                'version' => '23H2 (Build 22631)',
                'developer' => 'Microsoft Corporation',
                'license_type' => 'OEM_VOLUMEN',
                'license_key' => 'VK7JG-NPHTM-C97JM-9MPGT-3V66T',
                'total_licenses' => 150,
                'expiration_date' => null,
                'notes' => 'Licenciamiento institucional para estaciones de trabajo municipales',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'name' => 'Microsoft Office LTSC Professional Plus 2021',
                'version' => '21.0.14332.20546',
                'developer' => 'Microsoft Corporation',
                'license_type' => 'VOLUMEN_KMS',
                'license_key' => 'FXYTK-NJJ8C-GB6DW-3DYQT-6F7TH',
                'total_licenses' => 120,
                'expiration_date' => null,
                'notes' => 'Paquete ofimático Word, Excel, PowerPoint, Access corporativo',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 3,
                'name' => 'Kaspersky Endpoint Security for Business',
                'version' => '12.4 Cloud',
                'developer' => 'Kaspersky Lab',
                'license_type' => 'SUSCRIPCION_ANUAL',
                'license_key' => 'KASP-MDC-2026-9901-PROT',
                'total_licenses' => 200,
                'expiration_date' => '2026-12-31',
                'notes' => 'Antivirus y protección perimetral de puestos de trabajo municipal',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 4,
                'name' => 'AutoCAD 2024 Specialized Toolsets',
                'version' => '24.3',
                'developer' => 'Autodesk Inc.',
                'license_type' => 'SUSCRIPCION_CORPORATIVA',
                'license_key' => 'AUTO-MDC-OBRAS-2026',
                'total_licenses' => 15,
                'expiration_date' => '2027-03-30',
                'notes' => 'Para Gerencia de Desarrollo Urbano y Obras Públicas',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 5,
                'name' => 'QGIS Geographic Information System',
                'version' => '3.34 LTR Prizren',
                'developer' => 'QGIS Development Team',
                'license_type' => 'OPEN_SOURCE_GPL',
                'license_key' => 'OPEN-SOURCE-GPL-v3',
                'total_licenses' => 50,
                'expiration_date' => null,
                'notes' => 'Sistema de información geográfica para catastro y planeamiento territorial',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        DB::table('itam_software')->insert($software);

        $loans = [
            [
                'id' => 1,
                'loan_code' => 'PREST-2026-0001',
                'loan_number' => 'PREST-2026-0001',
                'requesting_office' => $gspOffice,
                'requesting_person_id' => $gspBossId,
                'borrower_name' => $gspBossName,
                'start_date' => '2026-10-01',
                'expected_return_date' => '2026-10-05',
                'actual_return_date' => null,
                'event_reason' => 'Sustentación de Plan Distrital de Manejo de Residuos Sólidos ante el Concejo Municipal.',
                'status' => 'ACTIVO',
                'return_condition' => null,
                'technician_name' => "Téc. {$techName} (ODT)",
                'notes' => 'Préstamo con cargador y mouse inalámbrico. Se emitió recordatorio de devolución.',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        DB::table('itam_asset_loans')->insert($loans);

        $loanItems = [
            [
                'id' => 1,
                'loan_id' => 1,
                'asset_id' => 6,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        DB::table('itam_asset_loan_items')->insert($loanItems);

        $movements = [
            [
                'id' => 1,
                'acta_number' => 'ACTA-ASIG-2026-0001',
                'asset_id' => 1,
                'from_office' => $almacenOffice,
                'to_office' => $odtOffice,
                'from_person_id' => $almacenBossId,
                'to_person_id' => $odtBossId,
                'movement_date' => '2026-01-15 09:30:00',
                'movement_type' => 'ASIGNACION',
                'reason' => 'Asignación oficial de equipamiento informático para funciones de desarrollo de sistemas.',
                'technician_name' => "Ing. {$techName} (ODT)",
                'notes' => 'Entrega de equipo informático completo con periféricos y estabilizador.',
                'include_children_in_transfer' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'acta_number' => 'ACTA-TRAS-2026-0002',
                'asset_id' => 1,
                'from_office' => $planeamientoOffice,
                'to_office' => $odtOffice,
                'from_person_id' => $planeamientoBossId,
                'to_person_id' => $odtBossId,
                'movement_date' => '2026-02-10 11:15:00',
                'movement_type' => 'TRASLADO',
                'reason' => 'Reestructuración interna y redistribución de recursos informáticos de alta capacidad.',
                'technician_name' => "Téc. {$techName} (Soporte TI)",
                'notes' => 'Traslado efectuado con conformidad de ambas jefaturas.',
                'include_children_in_transfer' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        DB::table('itam_asset_movements')->insert($movements);

        $audits = [
            [
                'id' => 1,
                'year' => 2026,
                'title' => 'Censo General de Activos TI - Año Fiscal 2026',
                'status' => 'EN_PROCESO',
                'start_date' => '2026-01-02',
                'close_date' => null,
                'snapshot_data' => '{"loaned": 1, "operative": 10, "maintenance": 1, "total_assets": 12}',
                'notes' => 'Inspección física obligatoria conforme a la Directiva de Control Patrimonial MDC N° 002-2026.',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        DB::table('itam_inventory_audits')->insert($audits);

        $maintenanceOrders = [
            [
                'id' => 1,
                'code' => 'OT-2026-0001',
                'order_number' => 'OT-2026-0001',
                'asset_id' => 8,
                'type' => 'CORRECTIVO',
                'status' => 'EN_PROCESO',
                'priority' => 'ALTA',
                'scheduled_date' => '2026-10-08',
                'completed_date' => null,
                'technician_name' => "Téc. {$techName} (ODT)",
                'diagnosis' => 'Rodillo de alimentación desgastado provoca atasco frecuente de papel bond 75g.',
                'activities_performed' => 'Desmontaje de bandeja 2, limpieza de sensor óptico de paso de papel.',
                'total_cost' => '85.00',
                'notes' => 'Se requiere repuesto de rodillos pickup roller originales.',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'code' => 'OT-2026-0002',
                'order_number' => 'OT-2026-0002',
                'asset_id' => 1,
                'type' => 'PREVENTIVO',
                'status' => 'COMPLETADO',
                'priority' => 'MEDIA',
                'scheduled_date' => '2026-09-15',
                'completed_date' => '2026-09-15',
                'technician_name' => "Ing. {$techName} (ODT)",
                'diagnosis' => 'Mantenimiento preventivo programado trimestral. Acumulación leve de polvo en cooler.',
                'activities_performed' => 'Sopleteado de fuente de poder, aplicación de pasta térmica Arctic MX-4, verificación de discos SMART.',
                'total_cost' => '35.00',
                'notes' => 'Equipo operativo al 100% con temperaturas de trabajo en 42°C.',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        DB::table('itam_maintenance_orders')->insert($maintenanceOrders);

        $maintenanceSupplies = [
            [
                'id' => 1,
                'maintenance_order_id' => 1,
                'supply_id' => 1,
                'quantity' => 1,
                'unit_cost' => '380.00',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        DB::table('itam_maintenance_supplies')->insert($maintenanceSupplies);

        if ($isPgsql) {
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_assets', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_assets;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_software', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_software;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_asset_loans', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_asset_loans;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_asset_loan_items', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_asset_loan_items;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_asset_movements', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_asset_movements;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_inventory_audits', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_inventory_audits;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_maintenance_orders', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_maintenance_orders;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_maintenance_supplies', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_maintenance_supplies;");
            DB::statement('SET session_replication_role = DEFAULT;');
        } else {
            Schema::enableForeignKeyConstraints();
        }
    }
}
