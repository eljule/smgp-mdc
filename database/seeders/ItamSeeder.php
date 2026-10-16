<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ItamSeeder extends Seeder
{
    public function run(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';
        if ($isPgsql) {
            DB::statement('SET session_replication_role = replica;');
        } else {
            Schema::disableForeignKeyConstraints();
        }

        DB::table('itam_supplies')->delete();
        DB::table('itam_asset_models')->delete();
        DB::table('itam_asset_brands')->delete();
        DB::table('itam_asset_categories')->delete();

        $categories = [
            [
                'id' => 1,
                'code' => 'PC',
                'name' => 'Computadora de Escritorio',
                'description' => 'Computadoras de escritorio tradicionales con case, CPU independiente y estaciones de trabajo.',
                'icon' => '🖥️',
                'color' => '#0284c7',
                'custom_fields_schema' => '[{"key": "processor", "type": "text", "label": "Procesador (CPU)", "required": true, "placeholder": "Ej. Intel Core i7-12700"}, {"key": "ram_gb", "type": "select", "label": "Memoria RAM", "options": ["4 GB", "8 GB", "16 GB", "32 GB", "64 GB"], "required": true}, {"key": "storage_primary", "type": "text", "label": "Disco Principal (SO)", "required": true, "placeholder": "Ej. 512 GB SSD NVMe M.2"}, {"key": "os", "type": "select", "label": "Sistema Operativo", "options": ["Windows 11 Pro 64-bit", "Windows 10 Pro 64-bit", "Ubuntu Linux 24.04 LTS"], "required": true}]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'code' => 'LAP',
                'name' => 'Computadora Portátil',
                'description' => 'Equipos portátiles para trabajo remoto, funcionarios y movilidad institucional.',
                'icon' => '💻',
                'color' => '#0d9488',
                'custom_fields_schema' => '[{"key": "processor", "type": "text", "label": "Procesador (CPU)", "required": true, "placeholder": "Ej. Intel Core i5-1135G7"}, {"key": "ram_gb", "type": "select", "label": "Memoria RAM", "options": ["8 GB", "16 GB", "32 GB"], "required": true}, {"key": "storage", "type": "text", "label": "Almacenamiento SSD", "required": true, "placeholder": "Ej. 512 GB SSD M.2"}, {"key": "screen_size", "type": "select", "label": "Tamaño de Pantalla", "options": ["13.3 Pulgadas", "14.0 Pulgadas", "15.6 Pulgadas"], "required": true}]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 3,
                'code' => 'MON',
                'name' => 'Monitor',
                'description' => 'Monitores y pantallas externas de visualización para puestos de trabajo.',
                'icon' => '📺',
                'color' => '#16a34a',
                'custom_fields_schema' => '[{"key": "screen_size_inches", "type": "number", "label": "Tamaño de Pantalla", "required": true, "placeholder": "24"}, {"key": "resolution", "type": "select", "label": "Resolución Nativa", "options": ["1366x768 (HD)", "1920x1080 (FHD)", "2560x1440 (2K)", "3840x2160 (4K)"], "required": true}]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 4,
                'code' => 'IMP',
                'name' => 'Equipo Multifuncional de Impresión',
                'description' => 'Equipos multifuncionales de impresión, escaneo y fotocopiado en red o USB.',
                'icon' => '🖨️',
                'color' => '#9333ea',
                'custom_fields_schema' => '[{"key": "print_type", "type": "select", "label": "Tecnología de Impresión", "options": ["Sistema Continuo de Tinta (EcoTank)", "Láser Monocromático (B/N)", "Láser a Color"], "required": true}, {"key": "connectivity", "type": "select", "label": "Conectividad", "options": ["Red Ethernet RJ45 + USB", "Wi-Fi + USB", "Solo Cable USB"], "required": true}]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 5,
                'code' => 'TEC',
                'name' => 'Teclado',
                'description' => 'Teclados cableados e inalámbricos para puestos de trabajo de oficina.',
                'icon' => '⌨️',
                'color' => '#ea580c',
                'custom_fields_schema' => '[{"key": "connector_type", "type": "select", "label": "Tipo de Conexión", "options": ["USB Cableado", "Inalámbrico 2.4GHz Dongle", "Bluetooth"], "required": true}]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 6,
                'code' => 'MOU',
                'name' => 'Mouse',
                'description' => 'Ratones ópticos y periféricos de puntero para computadoras.',
                'icon' => '🖱️',
                'color' => '#d97706',
                'custom_fields_schema' => '[{"key": "connector_type", "type": "select", "label": "Tipo de Conexión", "options": ["USB Cableado", "Inalámbrico 2.4GHz Dongle", "Bluetooth"], "required": true}]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 7,
                'code' => 'EST',
                'name' => 'Estabilizador',
                'description' => 'Estabilizadores de voltaje y reguladores para protección de equipos eléctricos.',
                'icon' => '⚡',
                'color' => '#e11d48',
                'custom_fields_schema' => '[{"key": "capacity_va", "type": "text", "label": "Capacidad de Potencia", "required": true, "placeholder": "1000VA"}, {"key": "voltage", "type": "select", "label": "Voltaje de Operación", "options": ["220V", "110V/220V Bi-voltaje"], "required": true}]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 8,
                'code' => 'SWT',
                'name' => 'Switch de Red',
                'description' => 'Conmutadores de red Ethernet para infraestructura de conectividad local.',
                'icon' => '🔀',
                'color' => '#4f46e5',
                'custom_fields_schema' => '[{"key": "ports_count", "type": "select", "label": "Número de Puertos", "options": ["8 Puertos", "16 Puertos", "24 Puertos", "48 Puertos"], "required": true}, {"key": "speed", "type": "select", "label": "Velocidad de Enlace", "options": ["Fast Ethernet (10/100 Mbps)", "Gigabit Ethernet (10/100/1000 Mbps)"], "required": true}]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 9,
                'code' => 'APR',
                'name' => 'Access Point / Router',
                'description' => 'Puntos de acceso inalámbrico y enrutadores de red institucional.',
                'icon' => '📡',
                'color' => '#0891b2',
                'custom_fields_schema' => '[{"key": "wifi_standard", "type": "select", "label": "Estándar Wi-Fi", "options": ["Wi-Fi 5 (802.11ac)", "Wi-Fi 6 (802.11ax)"], "required": true}, {"key": "frequency_bands", "type": "select", "label": "Bandas", "options": ["Doble Banda (2.4GHz + 5GHz)", "Solo 2.4GHz"], "required": true}]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        foreach (array_chunk($categories, 50) as $chunk) {
            DB::table('itam_asset_categories')->insert($chunk);
        }

        $brands = [
            [
                'id' => 1,
                'name' => 'HP',
                'description' => 'Hewlett-Packard computadoras, portátiles, monitores e impresoras.',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'name' => 'Lenovo',
                'description' => 'Equipos corporativos ThinkCentre y monitores ThinkVision.',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 3,
                'name' => 'Dell',
                'description' => 'Sistemas informáticos empresariales OptiPlex y monitores.',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 4,
                'name' => 'Epson',
                'description' => 'Impresoras EcoTank y multifuncionales de tinta continua.',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 5,
                'name' => 'Logitech',
                'description' => 'Periféricos ergonómicos, teclados y ratones.',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 6,
                'name' => 'TP-Link',
                'description' => 'Equipos de conectividad de red y switches.',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 7,
                'name' => 'Forza',
                'description' => 'Estabilizadores de voltaje y protección eléctrica.',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 8,
                'name' => 'GENÉRICO / POR DETERMINAR',
                'description' => 'Clones y compatibles',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        foreach (array_chunk($brands, 50) as $chunk) {
            DB::table('itam_asset_brands')->insert($chunk);
        }

        $models = [
            [
                'id' => 1,
                'name' => 'ProDesk 400 G7',
                'brand_id' => 1,
                'category_id' => 1,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'name' => 'ProBook 440 G9',
                'brand_id' => 1,
                'category_id' => 2,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 3,
                'name' => 'E24 G4 FHD',
                'brand_id' => 1,
                'category_id' => 3,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 4,
                'name' => 'LaserJet Pro MFP M428fdw',
                'brand_id' => 1,
                'category_id' => 4,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 5,
                'name' => 'ThinkCentre M70s Gen 3',
                'brand_id' => 2,
                'category_id' => 1,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 6,
                'name' => 'ThinkVision T24i-20',
                'brand_id' => 2,
                'category_id' => 3,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 7,
                'name' => 'ThinkPad L14 Gen 3',
                'brand_id' => 2,
                'category_id' => 2,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 8,
                'name' => 'OptiPlex 7010 SFF',
                'brand_id' => 3,
                'category_id' => 1,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 9,
                'name' => 'Latitude 3420',
                'brand_id' => 3,
                'category_id' => 2,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 10,
                'name' => 'EcoTank L3250',
                'brand_id' => 4,
                'category_id' => 4,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 11,
                'name' => 'WorkForce Pro WF-C5810',
                'brand_id' => 4,
                'category_id' => 4,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 12,
                'name' => 'K120 USB',
                'brand_id' => 5,
                'category_id' => 5,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 13,
                'name' => 'B100 USB',
                'brand_id' => 5,
                'category_id' => 6,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 14,
                'name' => 'TL-SF1024D 24-Port',
                'brand_id' => 6,
                'category_id' => 8,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 15,
                'name' => 'Archer C50 AC1200',
                'brand_id' => 6,
                'category_id' => 9,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 16,
                'name' => 'FVR-1202USB 1200VA',
                'brand_id' => 7,
                'category_id' => 7,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 17,
                'name' => 'P2419H 24 Pulgadas',
                'brand_id' => 3,
                'category_id' => 3,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 18,
                'name' => 'COMPATIBLE / ENSAMBLADO',
                'brand_id' => 8,
                'category_id' => 1,
                'description' => null,
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        foreach (array_chunk($models, 50) as $chunk) {
            DB::table('itam_asset_models')->insert($chunk);
        }

        $supplies = [
            [
                'id' => 1,
                'code' => 'INS-TNR-001',
                'name' => 'Tóner HP LaserJet 58A Negro Original (CF258A)',
                'category' => 'TONER_TINTA',
                'stock' => 8,
                'min_stock' => 2,
                'unit' => 'UNIDAD',
                'unit_cost' => '380.00',
                'location' => 'Almacén ODT - Estante 2',
                'compatible_models' => 'HP LaserJet Pro M404dw, M428fdw',
                'notes' => 'Insumo de alto rendimiento para despacho municipal',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'code' => 'INS-CAB-002',
                'name' => 'Bobina Cable de Red UTP Categoría 6 305m Dixon',
                'category' => 'CABLEADO_RED',
                'stock' => 3,
                'min_stock' => 1,
                'unit' => 'BOBINA',
                'unit_cost' => '450.00',
                'location' => 'Almacén ODT - Nivel 1',
                'compatible_models' => 'Cableado estructurado sedes',
                'notes' => 'Para ampliaciones de puntos de red municipal',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 3,
                'code' => 'INS-CON-003',
                'name' => 'Conectores RJ-45 Cat 6 x 100 Und',
                'category' => 'CONECTORES',
                'stock' => 12,
                'min_stock' => 3,
                'unit' => 'CAJA',
                'unit_cost' => '45.00',
                'location' => 'Almacén ODT - Gaveta 4',
                'compatible_models' => 'Cable Cat 6',
                'notes' => 'Conectores con contactos dorados 50u',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 4,
                'code' => 'INS-SSD-001',
                'name' => 'Disco Estado Sólido Kingston NV2 512GB NVMe M.2',
                'category' => 'REPUESTOS_HARDWARE',
                'stock' => 1,
                'min_stock' => 2,
                'unit' => 'UNIDAD',
                'unit_cost' => '165.00',
                'location' => 'Almacén ODT - Gabinete Repuestos',
                'compatible_models' => 'PCs y Laptops con zócalo M.2 NVMe',
                'notes' => 'Stock crítico: solo 1 unidad restante.',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 5,
                'code' => 'INS-RAM-001',
                'name' => 'Memoria RAM Kingston Fury Beast 16GB DDR4 3200MHz',
                'category' => 'REPUESTOS_HARDWARE',
                'stock' => 0,
                'min_stock' => 2,
                'unit' => 'UNIDAD',
                'unit_cost' => '155.00',
                'location' => 'Almacén ODT - Gabinete Repuestos',
                'compatible_models' => 'Computadoras OptiPlex y ProDesk',
                'notes' => 'Stock agotado. Requiere adquisición urgente.',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 6,
                'code' => 'INS-LIM-001',
                'name' => 'Alcohol Isopropílico 99.9% 1 Litro + Paños Microfibra',
                'category' => 'LIMPIEZA',
                'stock' => 6,
                'min_stock' => 2,
                'unit' => 'UNIDAD',
                'unit_cost' => '28.50',
                'location' => 'Almacén ODT - Gabinete Químicos',
                'compatible_models' => 'Mantenimiento preventivo general',
                'notes' => 'Solvente dieléctrico para limpieza de placas y rodillos.',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        foreach (array_chunk($supplies, 50) as $chunk) {
            DB::table('itam_supplies')->insert($chunk);
        }

        if ($isPgsql) {
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_asset_categories', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_asset_categories;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_asset_brands', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_asset_brands;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_asset_models', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_asset_models;");
            DB::statement("SELECT setval(pg_get_serial_sequence('itam_supplies', 'id'), coalesce(max(id), 0) + 1, false) FROM itam_supplies;");
            DB::statement('SET session_replication_role = DEFAULT;');
        } else {
            Schema::enableForeignKeyConstraints();
        }
    }
}
