<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Itam\Asset;
use App\Models\Itam\AssetBrand;
use App\Models\Itam\AssetCategory;
use App\Models\Itam\AssetLoan;
use App\Models\Itam\AssetLoanItem;
use App\Models\Itam\AssetModel;
use App\Models\Itam\AssetMovement;
use App\Models\Itam\InventoryAudit;
use App\Models\Itam\MaintenanceOrder;
use App\Models\Itam\MaintenanceSupply;
use App\Models\Itam\Software;
use App\Models\Itam\Supply;
use App\Models\Person;
use Illuminate\Database\Seeder;

class DemoItamSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAssetsWithPeripherals();
        $this->seedMovements();
        $this->seedMaintenanceOrders();
        $this->seedLoans();
        $this->seedAudits();
        $this->seedSoftware();
    }

    private function seedAssetsWithPeripherals(): void
    {
        $pcCat = AssetCategory::where('code', 'PC')->first();
        $lapCat = AssetCategory::where('code', 'LAP')->first();
        $monCat = AssetCategory::where('code', 'MON')->first();
        $impCat = AssetCategory::where('code', 'IMP')->first();
        $tecCat = AssetCategory::where('code', 'TEC')->first();
        $mouCat = AssetCategory::where('code', 'MOU')->first();
        $estCat = AssetCategory::where('code', 'EST')->first();
        $swtCat = AssetCategory::where('code', 'SWT')->first();

        $hpBrand = AssetBrand::where('name', 'HP')->first();
        $dellBrand = AssetBrand::where('name', 'Dell')->first();
        $lenovoBrand = AssetBrand::where('name', 'Lenovo')->first();
        $epsonBrand = AssetBrand::where('name', 'Epson')->first();
        $logiBrand = AssetBrand::where('name', 'Logitech')->first();
        $forzaBrand = AssetBrand::where('name', 'Forza')->first();
        $tplinkBrand = AssetBrand::where('name', 'TP-Link')->first();
        $genericBrand = AssetBrand::firstOrCreate(['name' => 'GENÉRICO / POR DETERMINAR'], ['description' => 'Clones y compatibles', 'is_active' => true]);

        $p1 = Person::first();
        $p2 = Person::skip(1)->first() ?? $p1;
        $p3 = Person::skip(2)->first() ?? $p1;

        // 1. PC Principal ODT (Padre)
        $dellModel = AssetModel::firstOrCreate(['name' => 'OptiPlex 7010 SFF', 'brand_id' => $dellBrand?->id], ['category_id' => $pcCat?->id, 'is_active' => true]);
        $pcAsset = Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-PC-0001'],
            [
                'patrimonial_code' => '7408770001',
                'serial_number' => '4CE1234XYZ',
                'category_id' => $pcCat?->id,
                'brand_id' => $dellBrand?->id,
                'model_id' => $dellModel?->id,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
                'assigned_person_id' => $p1?->id,
                'acquisition_date' => '2023-01-15',
                'supplier' => 'CORPORACIÓN TECNOLÓGICA DEL NORTE S.A.C.',
                'warranty_end_date' => '2026-01-15',
                'is_loanable' => false,
                'specifications' => [
                    'processor' => 'Intel Core i7-12700 2.10GHz',
                    'ram_gb' => '16 GB',
                    'storage_primary' => '512 GB SSD NVMe M.2',
                    'storage_secondary' => '1 TB HDD SATA',
                    'os' => 'Windows 11 Pro 64-bit',
                ],
                'notes' => 'Estación principal de administración de servidores ODT',
                'is_active' => true,
            ]
        );

        // Hijos de MDC-TI-PC-0001: Monitor, Teclado, Mouse, Estabilizador
        $dellMonModel = AssetModel::firstOrCreate(['name' => 'P2419H 24 Pulgadas', 'brand_id' => $dellBrand?->id], ['category_id' => $monCat?->id, 'is_active' => true]);
        Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-MON-0001'],
            [
                'patrimonial_code' => '7408770002',
                'serial_number' => 'CN-0ABC12-74261',
                'category_id' => $monCat?->id,
                'brand_id' => $dellBrand?->id,
                'model_id' => $dellMonModel?->id,
                'parent_asset_id' => $pcAsset->id,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
                'assigned_person_id' => $p1?->id,
                'is_loanable' => false,
                'specifications' => [
                    'screen_size_inches' => 24,
                    'resolution' => '1920x1080 (FHD)',
                    'panel_type' => 'IPS',
                    'video_ports' => 'HDMI, DisplayPort, VGA',
                ],
                'notes' => 'Monitor vinculado al puesto de administración ODT',
                'is_active' => true,
            ]
        );

        $k120Model = AssetModel::firstOrCreate(['name' => 'K120 USB', 'brand_id' => $logiBrand?->id], ['category_id' => $tecCat?->id, 'is_active' => true]);
        Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-TEC-0001'],
            [
                'patrimonial_code' => '7408770003',
                'serial_number' => '2145SC9812',
                'category_id' => $tecCat?->id,
                'brand_id' => $logiBrand?->id,
                'model_id' => $k120Model?->id,
                'parent_asset_id' => $pcAsset->id,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
                'assigned_person_id' => $p1?->id,
                'is_loanable' => false,
                'specifications' => ['connector_type' => 'USB Cableado', 'layout' => 'Español (Latinoamérica)'],
                'notes' => 'Teclado vinculado a MDC-TI-PC-0001',
                'is_active' => true,
            ]
        );

        $b100Model = AssetModel::firstOrCreate(['name' => 'B100 USB', 'brand_id' => $logiBrand?->id], ['category_id' => $mouCat?->id, 'is_active' => true]);
        Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-MOU-0001'],
            [
                'patrimonial_code' => '7408770004',
                'serial_number' => '1934LZ0012',
                'category_id' => $mouCat?->id,
                'brand_id' => $logiBrand?->id,
                'model_id' => $b100Model?->id,
                'parent_asset_id' => $pcAsset->id,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
                'assigned_person_id' => $p1?->id,
                'is_loanable' => false,
                'specifications' => ['connector_type' => 'USB Cableado', 'sensor_type' => 'Óptico LED', 'dpi' => '1000 DPI'],
                'notes' => 'Mouse óptico vinculado a MDC-TI-PC-0001',
                'is_active' => true,
            ]
        );

        $forzaEstModel = AssetModel::firstOrCreate(['name' => 'FVR-1202USB 1200VA', 'brand_id' => $forzaBrand?->id], ['category_id' => $estCat?->id, 'is_active' => true]);
        Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-EST-0012'],
            [
                'patrimonial_code' => '7408770005',
                'serial_number' => 'FZ1202-99881',
                'category_id' => $estCat?->id,
                'brand_id' => $forzaBrand?->id,
                'model_id' => $forzaEstModel?->id,
                'parent_asset_id' => $pcAsset->id,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
                'assigned_person_id' => $p1?->id,
                'is_loanable' => false,
                'specifications' => ['capacity_va' => '1200VA / 600W', 'voltage' => '220V', 'outlets_count' => 8],
                'notes' => 'Protección eléctrica de estación ODT',
                'is_active' => true,
            ]
        );

        // 2. Laptop Prestable
        $thinkpadModel = AssetModel::firstOrCreate(['name' => 'ThinkPad L14 Gen 3', 'brand_id' => $lenovoBrand?->id], ['category_id' => $lapCat?->id, 'is_active' => true]);
        Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-LAP-0001'],
            [
                'patrimonial_code' => '7408770010',
                'serial_number' => 'PF-389102',
                'category_id' => $lapCat?->id,
                'brand_id' => $lenovoBrand?->id,
                'model_id' => $thinkpadModel?->id,
                'color' => 'Negro Grafito',
                'status' => 'EN_PRESTAMO',
                'physical_condition' => 'BUENO',
                'office' => 'ALCALDÍA / SALA DE CONCEJO',
                'assigned_person_id' => $p2?->id,
                'acquisition_date' => '2023-04-10',
                'supplier' => 'LENOVO PERÚ S.A.',
                'warranty_end_date' => '2026-04-10',
                'is_loanable' => true,
                'specifications' => [
                    'processor' => 'AMD Ryzen 5 PRO 5675U 2.3GHz',
                    'ram_gb' => '16 GB',
                    'storage' => '512 GB SSD M.2 PCIe',
                    'screen_size' => '14.0 Pulgadas',
                    'has_charger' => true,
                    'os' => 'Windows 11 Pro 64-bit',
                ],
                'notes' => 'Equipo para contingencia institucional y comisiones de regidores',
                'is_active' => true,
            ]
        );

        // 3. Impresora EcoTank en Rentas
        $epsonModel = AssetModel::firstOrCreate(['name' => 'EcoTank L3250', 'brand_id' => $epsonBrand?->id], ['category_id' => $impCat?->id, 'is_active' => true]);
        Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-PRN-0001'],
            [
                'patrimonial_code' => '7408770020',
                'serial_number' => 'X9K8712390',
                'category_id' => $impCat?->id,
                'brand_id' => $epsonBrand?->id,
                'model_id' => $epsonModel?->id,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => 'GERENCIA DE ADMINISTRACIÓN TRIBUTARIA (RENTAS)',
                'assigned_person_id' => $p3?->id,
                'acquisition_date' => '2023-02-20',
                'supplier' => 'EPSON DEL PERÚ S.A.',
                'warranty_end_date' => '2025-02-20',
                'is_loanable' => false,
                'specifications' => [
                    'print_type' => 'Sistema Continuo de Tinta (EcoTank)',
                    'connectivity' => 'Wi-Fi + USB',
                    'network_ip' => '192.168.1.120',
                ],
                'notes' => 'Impresión de liquidaciones y formularios tributarios',
                'is_active' => true,
            ]
        );

        // 4. Impresora Departamental en Mantenimiento Técnico
        $hpImpModel = AssetModel::firstOrCreate(['name' => 'LaserJet Pro MFP M428fdw', 'brand_id' => $hpBrand?->id], ['category_id' => $impCat?->id, 'is_active' => true]);
        Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-PRN-0002'],
            [
                'patrimonial_code' => '7408770021',
                'serial_number' => 'VNB3400192',
                'category_id' => $impCat?->id,
                'brand_id' => $hpBrand?->id,
                'model_id' => $hpImpModel?->id,
                'color' => 'Blanco / Gris',
                'status' => 'EN_MANTENIMIENTO',
                'physical_condition' => 'REGULAR',
                'office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
                'assigned_person_id' => $p1?->id,
                'acquisition_date' => '2022-09-14',
                'supplier' => 'HP DIRECTO PERÚ',
                'is_loanable' => false,
                'specifications' => [
                    'print_type' => 'Láser Monocromático (B/N)',
                    'connectivity' => 'Ethernet + Wi-Fi + USB',
                    'network_ip' => '192.168.1.155',
                ],
                'notes' => 'Atasco recurrente de hojas en bandeja 2. En taller ODT para cambio de rodillos.',
                'is_active' => true,
            ]
        );

        // 5. Switch Rack de Comunicaciones
        $tplinkSwtModel = AssetModel::firstOrCreate(['name' => 'TL-SF1024D 24-Port', 'brand_id' => $tplinkBrand?->id], ['category_id' => $swtCat?->id, 'is_active' => true]);
        Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-SWT-0001'],
            [
                'patrimonial_code' => '7408770030',
                'serial_number' => '2194870021',
                'category_id' => $swtCat?->id,
                'brand_id' => $tplinkBrand?->id,
                'model_id' => $tplinkSwtModel?->id,
                'color' => 'Gris Oscuro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'BUENO',
                'office' => 'DATA CENTER / RACK PRINCIPAL ODT',
                'assigned_person_id' => $p1?->id,
                'acquisition_date' => '2021-11-05',
                'is_loanable' => false,
                'specifications' => [
                    'ports_count' => '24 Puertos',
                    'speed' => 'Fast Ethernet (10/100 Mbps)',
                    'poe_support' => false,
                ],
                'notes' => 'Switch de distribución para estaciones de gerencia municipal',
                'is_active' => true,
            ]
        );

        // 6. Equipo Clón por Verificar
        $genModel = AssetModel::firstOrCreate(['name' => 'COMPATIBLE / ENSAMBLADO', 'brand_id' => $genericBrand?->id], ['category_id' => $pcCat?->id, 'is_active' => true]);
        Asset::updateOrCreate(
            ['computer_code' => 'MDC-TI-PC-0002'],
            [
                'patrimonial_code' => null,
                'serial_number' => 'S/N-PROVISIONAL',
                'category_id' => $pcCat?->id,
                'brand_id' => $genericBrand?->id,
                'model_id' => $genModel?->id,
                'color' => 'Negro',
                'status' => 'OPERATIVO',
                'physical_condition' => 'REGULAR',
                'office' => 'SUBGERENCIA DE TRANSPORTES',
                'assigned_person_id' => $p2?->id,
                'acquisition_date' => '2020-05-12',
                'is_loanable' => false,
                'specifications' => [
                    'processor' => 'Intel Core i5-4460 3.20GHz',
                    'ram_gb' => '8 GB',
                    'storage_primary' => '240 GB SSD SATA',
                    'os' => 'Windows 10 Pro 64-bit',
                ],
                'notes' => 'Equipo clon ensamblado. Requiere regularización de código SBN patrimonial. ⚠️ VERIFICACIÓN PENDIENTE',
                'is_active' => true,
            ]
        );
    }

    private function seedMovements(): void
    {
        $pcAsset = Asset::where('computer_code', 'MDC-TI-PC-0001')->first();
        $p1 = Person::first();
        $p2 = Person::skip(1)->first() ?? $p1;

        if ($pcAsset && $p1) {
            AssetMovement::updateOrCreate(
                ['acta_number' => 'ACTA-ASIG-2026-0001'],
                [
                    'asset_id' => $pcAsset->id,
                    'from_office' => 'Almacén Central ODT',
                    'to_office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
                    'from_person_id' => null,
                    'to_person_id' => $p1->id,
                    'movement_date' => '2026-01-15 09:30:00',
                    'movement_type' => 'ASIGNACION',
                    'reason' => 'Asignación oficial de equipamiento informático para funciones de desarrollo de sistemas.',
                    'technician_name' => 'Ing. Juan Carlos Morales (ODT)',
                    'notes' => 'Entrega de equipo informático completo con periféricos y estabilizador.',
                    'include_children_in_transfer' => true,
                ]
            );

            AssetMovement::updateOrCreate(
                ['acta_number' => 'ACTA-TRAS-2026-0002'],
                [
                    'asset_id' => $pcAsset->id,
                    'from_office' => 'Oficina de Planeamiento',
                    'to_office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
                    'from_person_id' => $p2->id,
                    'to_person_id' => $p1->id,
                    'movement_date' => '2026-02-10 11:15:00',
                    'movement_type' => 'TRASLADO',
                    'reason' => 'Reestructuración interna y redistribución de recursos informáticos de alta capacidad.',
                    'technician_name' => 'Téc. Marco Aurelio Vargas (Soporte TI)',
                    'notes' => 'Traslado efectuado con conformidad de ambas jefaturas.',
                    'include_children_in_transfer' => true,
                ]
            );
        }
    }

    private function seedMaintenanceOrders(): void
    {
        $prnAsset = Asset::where('computer_code', 'MDC-TI-PRN-0002')->first();
        $pcAsset = Asset::where('computer_code', 'MDC-TI-PC-0001')->first();
        $tonerSupply = Supply::where('code', 'INS-TNR-001')->first();

        if ($prnAsset) {
            $order1 = MaintenanceOrder::updateOrCreate(
                ['order_number' => 'OT-2026-0001'],
                [
                    'code' => 'OT-2026-0001',
                    'asset_id' => $prnAsset->id,
                    'type' => 'CORRECTIVO',
                    'status' => 'EN_PROCESO',
                    'priority' => 'ALTA',
                    'scheduled_date' => '2026-10-08',
                    'technician_name' => 'Téc. Roberto Ruiz (ODT)',
                    'diagnosis' => 'Rodillo de alimentación desgastado provoca atasco frecuente de papel bond 75g.',
                    'activities_performed' => 'Desmontaje de bandeja 2, limpieza de sensor óptico de paso de papel.',
                    'total_cost' => 85.00,
                    'notes' => 'Se requiere repuesto de rodillos pickup roller originales.',
                ]
            );

            if ($tonerSupply) {
                MaintenanceSupply::updateOrCreate(
                    [
                        'maintenance_order_id' => $order1->id,
                        'supply_id' => $tonerSupply->id,
                    ],
                    [
                        'quantity' => 1,
                        'unit_cost' => 380.00,
                    ]
                );
            }
        }

        if ($pcAsset) {
            MaintenanceOrder::updateOrCreate(
                ['order_number' => 'OT-2026-0002'],
                [
                    'code' => 'OT-2026-0002',
                    'asset_id' => $pcAsset->id,
                    'type' => 'PREVENTIVO',
                    'status' => 'COMPLETADO',
                    'priority' => 'MEDIA',
                    'scheduled_date' => '2026-09-15',
                    'completed_date' => '2026-09-15',
                    'technician_name' => 'Ing. Juan Carlos Morales (ODT)',
                    'diagnosis' => 'Mantenimiento preventivo programado trimestral. Acumulación leve de polvo en cooler.',
                    'activities_performed' => 'Sopleteado de fuente de poder, aplicación de pasta térmica Arctic MX-4, verificación de discos SMART.',
                    'total_cost' => 35.00,
                    'notes' => 'Equipo operativo al 100% con temperaturas de trabajo en 42°C.',
                ]
            );
        }
    }

    private function seedLoans(): void
    {
        $lapAsset = Asset::where('computer_code', 'MDC-TI-LAP-0001')->first();
        $p2 = Person::skip(1)->first() ?? Person::first();

        if ($lapAsset && $p2) {
            $loan1 = AssetLoan::updateOrCreate(
                ['loan_code' => 'PREST-2026-0001'],
                [
                    'loan_number' => 'PREST-2026-0001',
                    'requesting_office' => 'GERENCIA DE SERVICIOS PÚBLICOS',
                    'requesting_person_id' => $p2->id,
                    'borrower_name' => $p2->full_name ?? 'Lic. Carmen Rosa Huamán',
                    'start_date' => '2026-10-01',
                    'expected_return_date' => '2026-10-05',
                    'event_reason' => 'Sustentación de Plan Distrital de Manejo de Residuos Sólidos ante el Concejo Municipal.',
                    'status' => 'ACTIVO',
                    'technician_name' => 'Téc. Marco Aurelio Vargas (ODT)',
                    'notes' => 'Préstamo con cargador y mouse inalámbrico. Se emitió recordatorio de devolución.',
                ]
            );

            AssetLoanItem::updateOrCreate([
                'loan_id' => $loan1->id,
                'asset_id' => $lapAsset->id,
            ]);
        }
    }

    private function seedAudits(): void
    {
        InventoryAudit::updateOrCreate(
            ['year' => 2026, 'title' => 'Censo General de Activos TI - Año Fiscal 2026'],
            [
                'status' => 'EN_PROCESO',
                'start_date' => '2026-01-02',
                'snapshot_data' => [
                    'total_assets' => 12,
                    'operative' => 10,
                    'maintenance' => 1,
                    'loaned' => 1,
                ],
                'notes' => 'Inspección física obligatoria conforme a la Directiva de Control Patrimonial MDC N° 002-2026.',
            ]
        );
    }

    private function seedSoftware(): void
    {
        $software = [
            [
                'name' => 'Microsoft Windows 11 Professional',
                'version' => '23H2 (Build 22631)',
                'developer' => 'Microsoft Corporation',
                'license_type' => 'OEM_VOLUMEN',
                'license_key' => 'VK7JG-NPHTM-C97JM-9MPGT-3V66T',
                'total_licenses' => 150,
                'expiration_date' => null,
                'notes' => 'Licenciamiento institucional para estaciones de trabajo municipales',
                'is_active' => true,
            ],
            [
                'name' => 'Microsoft Office LTSC Professional Plus 2021',
                'version' => '21.0.14332.20546',
                'developer' => 'Microsoft Corporation',
                'license_type' => 'VOLUMEN_KMS',
                'license_key' => 'FXYTK-NJJ8C-GB6DW-3DYQT-6F7TH',
                'total_licenses' => 120,
                'expiration_date' => null,
                'notes' => 'Paquete ofimático Word, Excel, PowerPoint, Access corporativo',
                'is_active' => true,
            ],
            [
                'name' => 'Kaspersky Endpoint Security for Business',
                'version' => '12.4 Cloud',
                'developer' => 'Kaspersky Lab',
                'license_type' => 'SUSCRIPCION_ANUAL',
                'license_key' => 'KASP-MDC-2026-9901-PROT',
                'total_licenses' => 200,
                'expiration_date' => '2026-12-31',
                'notes' => 'Antivirus y protección perimetral de puestos de trabajo municipal',
                'is_active' => true,
            ],
            [
                'name' => 'AutoCAD 2024 Specialized Toolsets',
                'version' => '24.3',
                'developer' => 'Autodesk Inc.',
                'license_type' => 'SUSCRIPCION_CORPORATIVA',
                'license_key' => 'AUTO-MDC-OBRAS-2026',
                'total_licenses' => 15,
                'expiration_date' => '2027-03-30',
                'notes' => 'Para Gerencia de Desarrollo Urbano y Obras Públicas',
                'is_active' => true,
            ],
            [
                'name' => 'QGIS Geographic Information System',
                'version' => '3.34 LTR Prizren',
                'developer' => 'QGIS Development Team',
                'license_type' => 'OPEN_SOURCE_GPL',
                'license_key' => 'OPEN-SOURCE-GPL-v3',
                'total_licenses' => 50,
                'expiration_date' => null,
                'notes' => 'Sistema de información geográfica para catastro y planeamiento territorial',
                'is_active' => true,
            ],
        ];

        foreach ($software as $sw) {
            Software::updateOrCreate(['name' => $sw['name']], $sw);
        }
    }
}
