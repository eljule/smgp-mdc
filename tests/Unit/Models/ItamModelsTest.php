<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Itam\Asset;
use App\Models\Itam\AssetBrand;
use App\Models\Itam\AssetCategory;
use App\Models\Itam\AssetModel;
use App\Models\Itam\MaintenanceOrder;
use App\Models\Itam\Supply;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItamModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_with_dynamic_json_schema(): void
    {
        $schema = [
            ['key' => 'ram_gb', 'label' => 'Memoria RAM', 'type' => 'select', 'options' => ['8 GB', '16 GB']],
        ];

        $category = AssetCategory::create([
            'code' => 'PC',
            'name' => 'Computadora de Escritorio',
            'custom_fields_schema' => $schema,
            'is_active' => true,
        ]);

        $this->assertIsArray($category->custom_fields_schema);
        $this->assertEquals('ram_gb', $category->custom_fields_schema[0]['key']);
    }

    public function test_brand_model_and_asset_relationships(): void
    {
        $category = AssetCategory::create(['code' => 'PC', 'name' => 'Computadora']);
        $brand = AssetBrand::create(['name' => 'HP']);
        $model = AssetModel::create(['name' => 'ProDesk 400', 'brand_id' => $brand->id, 'category_id' => $category->id]);
        $person = Person::create([
            'type' => 'PERSONAL',
            'document_type' => 'DNI',
            'document_number' => '11223344',
            'first_name' => 'Juan',
            'paternal_surname' => 'Perez',
        ]);

        $asset = Asset::create([
            'computer_code' => 'MDC-TI-001',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'assigned_person_id' => $person->id,
            'specifications' => ['ram' => '16GB', 'cpu' => 'Core i7'],
            'status' => 'OPERATIVO',
            'physical_condition' => 'BUENO',
        ]);

        $this->assertEquals('HP', $asset->brand->name);
        $this->assertEquals('ProDesk 400', $asset->model->name);
        $this->assertEquals('Computadora', $asset->category->name);
        $this->assertEquals('Juan Perez', $asset->assignedPerson->full_name);
        $this->assertEquals('16GB', $asset->specifications['ram']);
    }

    public function test_supply_and_maintenance_order(): void
    {
        $category = AssetCategory::create(['code' => 'IMP', 'name' => 'Impresora']);
        $brand = AssetBrand::create(['name' => 'Epson']);
        $model = AssetModel::create(['name' => 'L3250', 'brand_id' => $brand->id]);
        $asset = Asset::create([
            'computer_code' => 'MDC-IMP-001',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
        ]);

        $supply = Supply::create([
            'code' => 'INS-TNR-01',
            'name' => 'Tóner HP',
            'stock' => 5,
            'min_stock' => 2,
            'unit_cost' => 120.50,
        ]);

        $order = MaintenanceOrder::create([
            'code' => 'OT-2026-001',
            'asset_id' => $asset->id,
            'type' => 'PREVENTIVO',
            'status' => 'PROGRAMADO',
            'scheduled_date' => '2026-10-15',
            'total_cost' => 120.50,
        ]);

        $this->assertEquals('OT-2026-001', $order->code);
        $this->assertEquals($asset->id, $order->asset->id);
        $this->assertEquals(5, $supply->stock);
    }
}
