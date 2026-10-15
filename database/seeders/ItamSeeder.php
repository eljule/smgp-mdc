<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Itam\Asset;
use App\Models\Itam\AssetBrand;
use App\Models\Itam\AssetCategory;
use App\Models\Itam\AssetModel;
use App\Models\Itam\Supply;
use App\Models\Person;
use Illuminate\Database\Seeder;

class ItamSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedBrandsAndModels();
        $this->seedSupplies();
        $this->seedInitialAssets();
    }

    private function seedCategories(): void
    {
        $categories = [
            [
                'name' => 'Computadora de Escritorio',
                'code' => 'PC',
                'icon' => '🖥️',
                'color' => '#0284c7',
                'description' => 'Computadoras de escritorio tradicionales con case, CPU independiente y estaciones de trabajo.',
                'custom_fields_schema' => [
                    ['key' => 'processor', 'label' => 'Procesador (CPU)', 'type' => 'text', 'required' => true, 'placeholder' => 'Ej. Intel Core i7-12700'],
                    ['key' => 'ram_gb', 'label' => 'Memoria RAM', 'type' => 'select', 'required' => true, 'options' => ['4 GB', '8 GB', '16 GB', '32 GB', '64 GB']],
                    ['key' => 'storage_primary', 'label' => 'Disco Principal (SO)', 'type' => 'text', 'required' => true, 'placeholder' => 'Ej. 512 GB SSD NVMe M.2'],
                    ['key' => 'os', 'label' => 'Sistema Operativo', 'type' => 'select', 'required' => true, 'options' => ['Windows 11 Pro 64-bit', 'Windows 10 Pro 64-bit', 'Ubuntu Linux 24.04 LTS']],
                ],
            ],
            [
                'name' => 'Computadora Portátil',
                'code' => 'LAP',
                'icon' => '💻',
                'color' => '#0d9488',
                'description' => 'Equipos portátiles para trabajo remoto, funcionarios y movilidad institucional.',
                'custom_fields_schema' => [
                    ['key' => 'processor', 'label' => 'Procesador (CPU)', 'type' => 'text', 'required' => true, 'placeholder' => 'Ej. Intel Core i5-1135G7'],
                    ['key' => 'ram_gb', 'label' => 'Memoria RAM', 'type' => 'select', 'required' => true, 'options' => ['8 GB', '16 GB', '32 GB']],
                    ['key' => 'storage', 'label' => 'Almacenamiento SSD', 'type' => 'text', 'required' => true, 'placeholder' => 'Ej. 512 GB SSD M.2'],
                    ['key' => 'screen_size', 'label' => 'Tamaño de Pantalla', 'type' => 'select', 'required' => true, 'options' => ['13.3 Pulgadas', '14.0 Pulgadas', '15.6 Pulgadas']],
                ],
            ],
            [
                'name' => 'Monitor',
                'code' => 'MON',
                'icon' => '📺',
                'color' => '#16a34a',
                'description' => 'Monitores y pantallas externas de visualización para puestos de trabajo.',
                'custom_fields_schema' => [
                    ['key' => 'screen_size_inches', 'label' => 'Tamaño de Pantalla', 'type' => 'number', 'required' => true, 'placeholder' => '24'],
                    ['key' => 'resolution', 'label' => 'Resolución Nativa', 'type' => 'select', 'required' => true, 'options' => ['1366x768 (HD)', '1920x1080 (FHD)', '2560x1440 (2K)', '3840x2160 (4K)']],
                ],
            ],
            [
                'name' => 'Equipo Multifuncional de Impresión',
                'code' => 'IMP',
                'icon' => '🖨️',
                'color' => '#9333ea',
                'description' => 'Equipos multifuncionales de impresión, escaneo y fotocopiado en red o USB.',
                'custom_fields_schema' => [
                    ['key' => 'print_type', 'label' => 'Tecnología de Impresión', 'type' => 'select', 'required' => true, 'options' => ['Sistema Continuo de Tinta (EcoTank)', 'Láser Monocromático (B/N)', 'Láser a Color']],
                    ['key' => 'connectivity', 'label' => 'Conectividad', 'type' => 'select', 'required' => true, 'options' => ['Red Ethernet RJ45 + USB', 'Wi-Fi + USB', 'Solo Cable USB']],
                ],
            ],
            [
                'name' => 'Teclado',
                'code' => 'TEC',
                'icon' => '⌨️',
                'color' => '#ea580c',
                'description' => 'Teclados cableados e inalámbricos para puestos de trabajo de oficina.',
                'custom_fields_schema' => [
                    ['key' => 'connector_type', 'label' => 'Tipo de Conexión', 'type' => 'select', 'required' => true, 'options' => ['USB Cableado', 'Inalámbrico 2.4GHz Dongle', 'Bluetooth']],
                ],
            ],
            [
                'name' => 'Mouse',
                'code' => 'MOU',
                'icon' => '🖱️',
                'color' => '#d97706',
                'description' => 'Ratones ópticos y periféricos de puntero para computadoras.',
                'custom_fields_schema' => [
                    ['key' => 'connector_type', 'label' => 'Tipo de Conexión', 'type' => 'select', 'required' => true, 'options' => ['USB Cableado', 'Inalámbrico 2.4GHz Dongle', 'Bluetooth']],
                ],
            ],
            [
                'name' => 'Estabilizador',
                'code' => 'EST',
                'icon' => '⚡',
                'color' => '#e11d48',
                'description' => 'Estabilizadores de voltaje y reguladores para protección de equipos eléctricos.',
                'custom_fields_schema' => [
                    ['key' => 'capacity_va', 'label' => 'Capacidad de Potencia', 'type' => 'text', 'required' => true, 'placeholder' => '1000VA'],
                    ['key' => 'voltage', 'label' => 'Voltaje de Operación', 'type' => 'select', 'required' => true, 'options' => ['220V', '110V/220V Bi-voltaje']],
                ],
            ],
            [
                'name' => 'Switch de Red',
                'code' => 'SWT',
                'icon' => '🔀',
                'color' => '#4f46e5',
                'description' => 'Conmutadores de red Ethernet para infraestructura de conectividad local.',
                'custom_fields_schema' => [
                    ['key' => 'ports_count', 'label' => 'Número de Puertos', 'type' => 'select', 'required' => true, 'options' => ['8 Puertos', '16 Puertos', '24 Puertos', '48 Puertos']],
                    ['key' => 'speed', 'label' => 'Velocidad de Enlace', 'type' => 'select', 'required' => true, 'options' => ['Fast Ethernet (10/100 Mbps)', 'Gigabit Ethernet (10/100/1000 Mbps)']],
                ],
            ],
            [
                'name' => 'Access Point / Router',
                'code' => 'APR',
                'icon' => '📡',
                'color' => '#0891b2',
                'description' => 'Puntos de acceso inalámbrico y enrutadores de red institucional.',
                'custom_fields_schema' => [
                    ['key' => 'wifi_standard', 'label' => 'Estándar Wi-Fi', 'type' => 'select', 'required' => true, 'options' => ['Wi-Fi 5 (802.11ac)', 'Wi-Fi 6 (802.11ax)']],
                    ['key' => 'frequency_bands', 'label' => 'Bandas', 'type' => 'select', 'required' => true, 'options' => ['Doble Banda (2.4GHz + 5GHz)', 'Solo 2.4GHz']],
                ],
            ],
        ];

        foreach ($categories as $cat) {
            AssetCategory::updateOrCreate(['code' => $cat['code']], $cat);
        }
    }

    private function seedBrandsAndModels(): void
    {
        $brandsData = [
            [
                'name' => 'HP',
                'description' => 'Hewlett-Packard computadoras, portátiles, monitores e impresoras.',
                'models' => [
                    ['name' => 'ProDesk 400 G7', 'categoryCode' => 'PC'],
                    ['name' => 'ProBook 440 G9', 'categoryCode' => 'LAP'],
                    ['name' => 'E24 G4 FHD', 'categoryCode' => 'MON'],
                    ['name' => 'LaserJet Pro MFP M428fdw', 'categoryCode' => 'IMP'],
                ],
            ],
            [
                'name' => 'Lenovo',
                'description' => 'Equipos corporativos ThinkCentre y monitores ThinkVision.',
                'models' => [
                    ['name' => 'ThinkCentre M70s Gen 3', 'categoryCode' => 'PC'],
                    ['name' => 'ThinkVision T24i-20', 'categoryCode' => 'MON'],
                    ['name' => 'ThinkPad L14 Gen 3', 'categoryCode' => 'LAP'],
                ],
            ],
            [
                'name' => 'Dell',
                'description' => 'Sistemas informáticos empresariales OptiPlex y monitores.',
                'models' => [
                    ['name' => 'OptiPlex 7010 SFF', 'categoryCode' => 'PC'],
                    ['name' => 'Latitude 3420', 'categoryCode' => 'LAP'],
                ],
            ],
            [
                'name' => 'Epson',
                'description' => 'Impresoras EcoTank y multifuncionales de tinta continua.',
                'models' => [
                    ['name' => 'EcoTank L3250', 'categoryCode' => 'IMP'],
                    ['name' => 'WorkForce Pro WF-C5810', 'categoryCode' => 'IMP'],
                ],
            ],
            [
                'name' => 'Logitech',
                'description' => 'Periféricos ergonómicos, teclados y ratones.',
                'models' => [
                    ['name' => 'K120 USB', 'categoryCode' => 'TEC'],
                    ['name' => 'B100 USB', 'categoryCode' => 'MOU'],
                ],
            ],
            [
                'name' => 'TP-Link',
                'description' => 'Equipos de conectividad de red y switches.',
                'models' => [
                    ['name' => 'TL-SF1024D 24-Port', 'categoryCode' => 'SWT'],
                    ['name' => 'Archer C50 AC1200', 'categoryCode' => 'APR'],
                ],
            ],
            [
                'name' => 'Forza',
                'description' => 'Estabilizadores de voltaje y protección eléctrica.',
                'models' => [
                    ['name' => 'FVR-1202USB 1200VA', 'categoryCode' => 'EST'],
                ],
            ],
        ];

        foreach ($brandsData as $b) {
            $brand = AssetBrand::updateOrCreate(
                ['name' => $b['name']],
                ['description' => $b['description'], 'is_active' => true]
            );

            foreach ($b['models'] as $m) {
                $cat = AssetCategory::where('code', $m['categoryCode'])->first();
                AssetModel::updateOrCreate(
                    ['name' => $m['name'], 'brand_id' => $brand->id],
                    ['category_id' => $cat?->id, 'is_active' => true]
                );
            }
        }
    }

    private function seedSupplies(): void
    {
        $supplies = [
            [
                'code' => 'INS-TNR-001',
                'name' => 'Tóner HP LaserJet 58A Negro Original (CF258A)',
                'category' => 'TONER_TINTA',
                'stock' => 8,
                'min_stock' => 2,
                'unit' => 'UNIDAD',
                'unit_cost' => 380.00,
                'location' => 'Almacén ODT - Estante 2',
                'compatible_models' => 'HP LaserJet Pro M404dw, M428fdw',
                'notes' => 'Insumo de alto rendimiento para despacho municipal',
            ],
            [
                'code' => 'INS-CAB-002',
                'name' => 'Bobina Cable de Red UTP Categoría 6 305m Dixon',
                'category' => 'CABLEADO_RED',
                'stock' => 3,
                'min_stock' => 1,
                'unit' => 'BOBINA',
                'unit_cost' => 450.00,
                'location' => 'Almacén ODT - Nivel 1',
                'compatible_models' => 'Cableado estructurado sedes',
                'notes' => 'Para ampliaciones de puntos de red municipal',
            ],
            [
                'code' => 'INS-CON-003',
                'name' => 'Conectores RJ-45 Cat 6 x 100 Und',
                'category' => 'CONECTORES',
                'stock' => 12,
                'min_stock' => 3,
                'unit' => 'CAJA',
                'unit_cost' => 45.00,
                'location' => 'Almacén ODT - Gaveta 4',
                'compatible_models' => 'Cable Cat 6',
                'notes' => 'Conectores con contactos dorados 50u',
            ],
        ];

        foreach ($supplies as $s) {
            Supply::updateOrCreate(['code' => $s['code']], $s);
        }
    }

    private function seedInitialAssets(): void
    {
        $pcCat = AssetCategory::where('code', 'PC')->first();
        $hpBrand = AssetBrand::where('name', 'HP')->first();
        $pcModel = AssetModel::where('name', 'ProDesk 400 G7')->first();
        $personMendoza = Person::where('document_number', '10783864')->first() ?? Person::where('office', 'like', '%[ODT]%')->first() ?? Person::first();

        if ($pcCat && $hpBrand && $pcModel) {
            Asset::updateOrCreate(
                ['computer_code' => 'MDC-TI-PC-0001'],
                [
                    'patrimonial_code' => '7408770001',
                    'serial_number' => '4CE1234XYZ',
                    'category_id' => $pcCat->id,
                    'brand_id' => $hpBrand->id,
                    'model_id' => $pcModel->id,
                    'color' => 'Negro',
                    'status' => 'OPERATIVO',
                    'physical_condition' => 'BUENO',
                    'office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
                    'assigned_person_id' => $personMendoza?->id,
                    'acquisition_date' => '2023-01-15',
                    'supplier' => 'CORPORACIÓN TECNOLÓGICA DEL NORTE S.A.C.',
                    'warranty_end_date' => '2026-01-15',
                    'is_loanable' => false,
                    'specifications' => [
                        'processor' => 'Intel Core i7-10700 2.9GHz',
                        'ram_gb' => '16 GB',
                        'storage_primary' => '512 GB SSD NVMe M.2',
                        'os' => 'Windows 11 Pro 64-bit',
                    ],
                    'notes' => 'Equipo principal del Administrador de Sistemas',
                    'is_active' => true,
                ]
            );
        }
    }
}
