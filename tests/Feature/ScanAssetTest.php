<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Helpdesk\NewTicket;
use App\Models\Itam\Asset;
use App\Models\Itam\AssetBrand;
use App\Models\Itam\AssetCategory;
use App\Models\Itam\AssetModel;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ScanAssetTest extends TestCase
{
    use RefreshDatabase;

    private function createAssetFixture(array $attributes = []): Asset
    {
        $category = AssetCategory::firstOrCreate(
            ['code' => 'PC'],
            ['name' => 'Computadoras de Escritorio']
        );
        $brand = AssetBrand::firstOrCreate(['name' => 'Dell']);
        $model = AssetModel::firstOrCreate(
            ['name' => 'OptiPlex 7090', 'brand_id' => $brand->id],
            ['category_id' => $category->id]
        );

        return Asset::create(array_merge([
            'computer_code' => 'MDC-TI-PC-0001',
            'patrimonial_code' => '740877000123',
            'serial_number' => 'SN-12345',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'office' => 'SUBGERENCIA DE INFORMÁTICA',
            'status' => 'OPERATIVO',
            'physical_condition' => 'EXCELENTE',
        ], $attributes));
    }

    public function test_renders_asset_sheet_by_computer_code_successfully(): void
    {
        $person = Person::create([
            'type' => 'PERSONAL',
            'first_name' => 'Juan',
            'paternal_surname' => 'Pérez',
            'maternal_surname' => 'Gómez',
            'document_type' => 'DNI',
            'document_number' => '45678912',
            'office' => 'SUBGERENCIA DE INFORMÁTICA',
            'position' => 'Especialista en Redes',
        ]);

        $this->createAssetFixture([
            'computer_code' => 'MDC-TI-PC-9999',
            'patrimonial_code' => '740877000123',
            'assigned_person_id' => $person->id,
            'specifications' => [
                'procesador' => 'Intel Core i7-11700',
                'ram' => '16 GB DDR4',
                'almacenamiento' => '512 GB SSD NVMe',
            ],
        ]);

        $response = $this->get('/scan/activo/MDC-TI-PC-9999');

        $response->assertStatus(200);
        $response->assertSee('MDC-TI-PC-9999');
        $response->assertSee('740877000123');
        $response->assertSee('Dell');
        $response->assertSee('OptiPlex 7090');
        $response->assertSee('Juan Pérez Gómez');
        $response->assertSee('SUBGERENCIA DE INFORMÁTICA');
        $response->assertSee('Intel Core i7-11700');
        $response->assertSee('Reportar Falla o Solicitar Soporte');
    }

    public function test_renders_asset_sheet_by_patrimonial_code(): void
    {
        $this->createAssetFixture([
            'computer_code' => 'MDC-TI-MON-8888',
            'patrimonial_code' => '740899000456',
            'status' => 'DISPONIBLE',
        ]);

        $response = $this->get('/scan/activo/740899000456');

        $response->assertStatus(200);
        $response->assertSee('MDC-TI-MON-8888');
    }

    public function test_returns_404_when_asset_not_found(): void
    {
        $response = $this->get('/scan/activo/INEXISTENTE-12345');

        $response->assertStatus(404);
    }

    public function test_new_ticket_page_prefills_asset_when_scanned_via_qr(): void
    {
        $this->createAssetFixture([
            'computer_code' => 'MDC-TI-LAP-5555',
            'patrimonial_code' => '740811000789',
            'office' => 'GERENCIA MUNICIPAL',
            'status' => 'OPERATIVO',
        ]);

        Livewire::test(NewTicket::class, ['asset' => 'MDC-TI-LAP-5555'])
            ->assertSet('selectedAssetCode', 'MDC-TI-LAP-5555')
            ->assertSet('officeName', 'GERENCIA MUNICIPAL');
    }
}
