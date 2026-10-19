<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\Itam\Dashboard;
use App\Models\Itam\Asset;
use App\Models\Itam\AssetBrand;
use App\Models\Itam\AssetCategory;
use App\Models\Itam\AssetModel;
use App\Models\Itam\InventoryAudit;
use App\Models\Itam\Supply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ItamComponentsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_renders_itam_dashboard_successfully(): void
    {
        $response = $this->get('/itam');

        $response->assertStatus(200);
        $response->assertSee('Inventario y Gestión TI (ITAM)');
        $response->assertSee('Total Equipos');
        $response->assertSee('Parque Informático por Categoría');
    }

    public function test_renders_itam_assets_page_and_searches(): void
    {
        $response = $this->get('/itam/activos');
        $response->assertRedirect('/itam?tab=inventory');
    }

    public function test_renders_itam_supplies_and_adjusts_stock(): void
    {
        $response = $this->get('/itam/insumos');
        $response->assertRedirect('/itam?tab=supplies');
    }

    public function test_renders_itam_loans_and_registers_loan(): void
    {
        $response = $this->get('/itam/prestamos');
        $response->assertRedirect('/itam?tab=loans');
    }

    public function test_dashboard_supplies_creation_and_adjustment(): void
    {
        Livewire::test(Dashboard::class)
            ->call('openSupplyModal')
            ->assertSet('isSupplyModalOpen', true)
            ->assertSet('formSupplyStock', 10)
            ->assertSet('formSupplyMinStock', 2)
            ->set('formSupplyName', 'Bobina UTP Cat6 305m Dixon Especial')
            ->set('formSupplyCategory', 'CABLEADO_RED')
            ->set('formSupplyUnit', 'BOBINA')
            ->set('formSupplyLocation', 'Almacén ODT - Estante 2')
            ->set('formSupplyNotes', 'Para cableado de oficinas en piso 2')
            ->call('saveSupply')
            ->assertSet('isSupplyModalOpen', false);

        $this->assertDatabaseHas('itam_supplies', [
            'name' => 'Bobina UTP Cat6 305m Dixon Especial',
            'category' => 'CABLEADO_RED',
            'unit' => 'BOBINA',
        ]);
    }

    public function test_dashboard_loan_creation_and_acta(): void
    {
        $asset = Asset::first();
        $asset->update(['is_loanable' => true]);

        Livewire::test(Dashboard::class)
            ->call('openLoanModal')
            ->assertSet('isLoanModalOpen', true)
            ->set('loanDepartment', 'Gerencia de Rentas')
            ->set('loanBorrowerName', 'Lic. Carlos Benites')
            ->set('loanStartDate', '2026-10-11')
            ->set('loanExpectedReturnDate', '2026-10-13')
            ->set('loanReason', 'Capacitación sobre nuevo TUPA en auditorio municipal')
            ->set('loanNotes', 'Incluye puntero láser y cable HDMI de 5m')
            ->set('loanSelectedAssetIds', [$asset->id])
            ->call('saveLoan')
            ->assertSet('isLoanModalOpen', false);

        $this->assertDatabaseHas('itam_asset_loans', [
            'requesting_office' => 'Gerencia de Rentas',
            'borrower_name' => 'Lic. Carlos Benites',
        ]);
    }

    public function test_dashboard_annual_inventory_audit_opening(): void
    {
        Livewire::test(Dashboard::class)
            ->call('openAuditModal')
            ->assertSet('isAuditModalOpen', true)
            ->assertSet('formAuditYear', 2026)
            ->assertSet('formAuditTitle', 'Inventario Físico Anual 2026')
            ->set('formAuditNotes', 'Conforme a Resolución de Alcaldía N° 045-2026-MDC/A')
            ->call('saveAudit')
            ->assertSet('isAuditModalOpen', false);

        $this->assertDatabaseHas('itam_inventory_audits', [
            'year' => 2026,
            'title' => 'Inventario Físico Anual 2026',
            'status' => 'EN_PROCESO',
        ]);
    }

    public function test_dashboard_software_registration(): void
    {
        Livewire::test(Dashboard::class)
            ->call('openSoftwareModal')
            ->assertSet('isSoftwareModalOpen', true)
            ->assertSet('formSoftwareLicenseType', 'OEM')
            ->assertSet('formSoftwareTotalLicenses', 1)
            ->set('formSoftwareName', 'AutoCAD 2024')
            ->set('formSoftwareVersion', '2024.1')
            ->set('formSoftwareDeveloper', 'Autodesk')
            ->set('formSoftwareLicenseType', 'VOLUMEN')
            ->set('formSoftwareTotalLicenses', 5)
            ->set('formSoftwareLicenseKey', '5678-ABCD-EFGH-1234')
            ->set('formSoftwareNotes', 'Licencia para Subgerencia de Obras y Catastro')
            ->call('saveSoftware')
            ->assertSet('isSoftwareModalOpen', false);

        $this->assertDatabaseHas('itam_software', [
            'name' => 'AutoCAD 2024',
            'developer' => 'Autodesk',
            'license_type' => 'VOLUMEN',
            'total_licenses' => 5,
        ]);
    }

    public function test_dashboard_category_creation_and_update(): void
    {
        $test = Livewire::test(Dashboard::class)
            ->call('openCategoryModal')
            ->assertSet('isCategoryModalOpen', true)
            ->assertSet('formCatIcon', '💻')
            ->assertSet('formCatColor', '#2563eb')
            ->set('formCatName', 'Switch Administrable')
            ->assertSet('formCatCode', 'SWA')
            ->set('formCatCode', 'SWX')
            ->set('formCatIcon', '🔌')
            ->set('formCatColor', '#16a34a')
            ->set('formCatDescription', 'Equipos de comunicaciones y networking')
            ->call('saveCategory')
            ->assertSet('isCategoryModalOpen', false);

        $this->assertDatabaseHas('itam_asset_categories', [
            'name' => 'Switch Administrable',
            'code' => 'SWX',
            'icon' => '🔌',
            'color' => '#16a34a',
            'description' => 'Equipos de comunicaciones y networking',
        ]);

        $cat = AssetCategory::where('code', 'SWX')->first();

        $test->call('openCategoryModal', $cat->id)
            ->assertSet('isCategoryModalOpen', true)
            ->assertSet('editingCategoryId', $cat->id)
            ->assertSet('formCatName', 'Switch Administrable')
            ->set('formCatDescription', 'Switches y routers core municipal')
            ->call('saveCategory')
            ->assertSet('isCategoryModalOpen', false);

        $this->assertDatabaseHas('itam_asset_categories', [
            'id' => $cat->id,
            'description' => 'Switches y routers core municipal',
        ]);
    }

    public function test_dashboard_category_schema_configuration(): void
    {
        $cat = AssetCategory::where('code', 'PC')->first() ?? AssetCategory::first();

        Livewire::test(Dashboard::class)
            ->call('openCategorySchemaModal', $cat->id)
            ->assertSet('isCategorySchemaModalOpen', true)
            ->assertSet('schemaTab', 'editor')
            ->call('addSchemaField')
            ->set('schemaFields.0.label', 'Procesador Principal')
            ->set('schemaFields.0.key', 'cpu_model')
            ->set('schemaFields.0.type', 'text')
            ->set('schemaFields.0.required', true)
            ->set('schemaTab', 'preview')
            ->assertSet('schemaTab', 'preview')
            ->set('schemaTab', 'editor')
            ->call('moveSchemaField', 0, 'down')
            ->call('reorderSchemaField', 1, 0)
            ->call('saveCategorySchema')
            ->assertSet('isCategorySchemaModalOpen', false);

        $cat->refresh();
        $this->assertNotEmpty($cat->custom_fields_schema);
        $keys = array_column($cat->custom_fields_schema, 'key');
        $this->assertContains('cpu_model', $keys);
    }

    public function test_dashboard_brand_and_model_catalogs(): void
    {
        // 1. Crear marca
        $test = Livewire::test(Dashboard::class)
            ->call('openBrandModal')
            ->assertSet('isBrandModelModalOpen', true)
            ->assertSet('catalogType', 'brand')
            ->set('formBrandName', 'Cisco Systems')
            ->set('formBrandDescription', 'Equipos de enrutamiento y switching empresarial')
            ->call('saveBrandOrModel')
            ->assertSet('isBrandModelModalOpen', false);

        $this->assertDatabaseHas('itam_asset_brands', [
            'name' => 'Cisco Systems',
            'description' => 'Equipos de enrutamiento y switching empresarial',
        ]);

        $brand = AssetBrand::where('name', 'Cisco Systems')->first();

        // 2. Editar marca
        $test->call('openBrandModal', $brand->id)
            ->assertSet('isBrandModelModalOpen', true)
            ->assertSet('editingBrandId', $brand->id)
            ->set('formBrandDescription', 'Equipamiento de networking core')
            ->call('saveBrandOrModel')
            ->assertSet('isBrandModelModalOpen', false);

        $this->assertDatabaseHas('itam_asset_brands', [
            'id' => $brand->id,
            'description' => 'Equipamiento de networking core',
        ]);

        // 3. Crear modelo
        $test->call('openModelModal')
            ->assertSet('isBrandModelModalOpen', true)
            ->assertSet('catalogType', 'model')
            ->set('formModelBrandId', $brand->id)
            ->set('formModelName', 'Catalyst 9200L')
            ->set('formModelDescription', 'Switch de acceso 24 puertos Gigabit')
            ->call('saveBrandOrModel')
            ->assertSet('isBrandModelModalOpen', false);

        $this->assertDatabaseHas('itam_asset_models', [
            'brand_id' => $brand->id,
            'name' => 'Catalyst 9200L',
        ]);

        $model = AssetModel::where('name', 'Catalyst 9200L')->first();

        // 4. Editar modelo
        $test->call('openModelModal', $model->id)
            ->assertSet('isBrandModelModalOpen', true)
            ->assertSet('editingModelId', $model->id)
            ->set('formModelDescription', 'Switch capa 3 administrable')
            ->call('saveBrandOrModel')
            ->assertSet('isBrandModelModalOpen', false);

        $this->assertDatabaseHas('itam_asset_models', [
            'id' => $model->id,
            'description' => 'Switch capa 3 administrable',
        ]);

        // 5. Eliminar modelo y marca
        $test->call('deleteModel', $model->id);
        $this->assertDatabaseMissing('itam_asset_models', ['id' => $model->id]);

        $test->call('deleteBrand', $brand->id);
        $this->assertDatabaseMissing('itam_asset_brands', ['id' => $brand->id]);
    }

    public function test_dashboard_kpis_metrics_rendering(): void
    {
        Livewire::test(Dashboard::class)
            ->call('setTab', 'metrics')
            ->assertSet('activeTab', 'metrics')
            ->assertSee('Dashboard Ejecutivo de Indicadores TI (KPIs)')
            ->assertSee('DISPONIBILIDAD TI')
            ->assertSee('INSUMOS CRÍTICOS')
            ->assertSee('MANTENIMIENTOS PENDIENTES')
            ->assertSee('PRÉSTAMOS RETRASADOS')
            ->assertSee('Distribución de Activos por Categoría')
            ->assertSee('Estado Operativo del Parque Informático')
            ->assertSee('Operativo')
            ->assertSee('En Mantenimiento');
    }

    public function test_renders_all_other_itam_views(): void
    {
        $this->get('/itam/mantenimiento')->assertRedirect('/itam?tab=maintenance');
        $this->get('/itam/movimientos')->assertRedirect('/itam?tab=movements');
        $this->get('/itam/software')->assertRedirect('/itam?tab=software');
        $this->get('/itam/auditoria')->assertRedirect('/itam?tab=audits');
    }

    public function test_asset_detail_modal_ficha_tecnica_rendering(): void
    {
        $asset = Asset::with(['category', 'brand', 'model', 'assignedPerson', 'childAssets'])->first();
        $this->assertNotNull($asset);

        Livewire::test(Dashboard::class)
            ->call('openDetailModal', $asset->id)
            ->assertSet('isDetailModalOpen', true)
            ->assertSee('Ficha Técnica Patrimonial e Informática')
            ->assertSee('Municipalidad Distrital de Castilla')
            ->assertSee('Código Patrimonial SBN')
            ->assertSee('Número de Serie')
            ->assertSee('Condición Física')
            ->assertSee('Asignación y Custodia Institucional')
            ->assertSee('Oficina / Dependencia')
            ->assertSee('Custodio Responsable')
            ->assertSee('SUBCOMPONENTES VINCULADOS (PADRE - HIJO)')
            ->assertSee('Gestionar Periféricos')
            ->assertSee('ESPECIFICACIONES TÉCNICAS REGISTRADAS')
            ->assertSee('Esquema Dinámico')
            ->assertSee('Acta Oficial PDF')
            ->assertSee('Trasladar / Asignar')
            ->assertSee('Editar Activo');
    }

    public function test_audit_reconciliation_modal_rendering(): void
    {
        $audit = InventoryAudit::first();
        $this->assertNotNull($audit);

        Livewire::test(Dashboard::class)
            ->call('setTab', 'audits')
            ->assertSee('Control de Inventario Anual', false)
            ->assertSee('Pistola QR / Conciliación en Campo')
            ->call('openAuditReconciliationModal', $audit->id)
            ->assertSet('isAuditReconciliationModalOpen', true)
            ->assertSee('Auditoría', false)->assertSee('Conciliación Física', false)
            ->assertSee('TOTAL ESPERADOS')
            ->assertSee('CONCILIADOS')
            ->assertSee('TRASLADOS NO AUT.')
            ->assertSee('PROGRESO FÍSICO')
            ->assertSee('LEVANTAMIENTO RÁPIDO EN CAMPO')
            ->assertSee('DETALLE DE ACTIVOS DEL SNAPSHOT')
            ->assertSee('MDC-TI-PC-0001')
            ->assertSee('Cerrar Inventario Anual Definitivo');
    }

    public function test_acta_document_modal_rendering(): void
    {
        $asset = Asset::with(['category', 'brand', 'model', 'assignedPerson', 'childAssets'])->first();
        $this->assertNotNull($asset);

        Livewire::test(Dashboard::class)
            ->call('openActaModal', 'ASIGNACION', $asset->id)
            ->assertSet('isActaModalOpen', true)
            ->assertSee('DOCUMENTACIÓN OFICIAL DIGITAL')
            ->assertSee('ACTA DE ASIGNACIÓN Y ENTREGA DE EQUIPO TI')
            ->assertSee('MUNICIPALIDAD DISTRITAL DE CASTILLA')
            ->assertSee('OFICINA DE TECNOLOGÍAS DE LA INFORMACIÓN Y SISTEMAS')
            ->assertSee('1. DATOS GENERALES DE LA OPERACIÓN')
            ->assertSee('2. ESPECIFICACIONES TÉCNICAS DEL ACTIVO PRINCIPAL')
            ->assertSee('DECLARACIÓN DE CONFORMIDAD')
            ->assertSee('Entrega Conforme')
            ->assertSee('Recepción y Conformidad')
            ->assertSee('Imprimir / Guardar PDF');
    }
    public function test_asset_modal_datalists_and_custodian_resolution(): void
    {
        $person = \App\Models\Person::whereNotNull('office')->first();
        $this->assertNotNull($person);

        $testComponent = Livewire::test(Dashboard::class)
            ->call('openAssetModal')
            ->assertSet('isAssetModalOpen', true)
            ->assertSee('modal-municipal-offices')
            ->assertSee('modal-custodians')
            // 1. Elegir custodio actualiza automáticamente la oficina
            ->set('formAssignedPersonName', "{$person->full_name} ({$person->document_number})")
            ->assertSet('formAssignedPersonId', $person->id);

        $resolvedOffice = $testComponent->get('formOffice');
        $this->assertNotEmpty($resolvedOffice);

        // 2. Elegir oficina actualiza automáticamente el custodio
        $office = \App\Models\Office::first();
        if ($office) {
            $resp = $office->resolveActiveResponsible();
            if (! empty($resp['person'])) {
                $testComponent->set('formOffice', "[{$office->acronym}] {$office->name} - {$office->sede}")
                    ->assertSet('formAssignedPersonId', $resp['person']->id);
            }
        }

        // 3. Quitar custodio
        $testComponent->set('formAssignedPersonName', '-- Sin Custodio Asignado (Almacén TI) --')
            ->assertSet('formAssignedPersonId', null);
    }
    public function test_child_assets_modal_and_linking_without_lazy_loading_violation(): void
    {
        $category = \App\Models\Itam\AssetCategory::firstOrCreate(
            ['code' => 'TESTCAT'],
            ['name' => 'Categoría Test', 'icon' => '🖥️']
        );
        $brand = \App\Models\Itam\AssetBrand::firstOrCreate(['name' => 'Brand Test']);
        $model = \App\Models\Itam\AssetModel::firstOrCreate(
            ['name' => 'Model Test', 'brand_id' => $brand->id],
            ['category_id' => $category->id]
        );

        $parent = \App\Models\Itam\Asset::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'status' => 'OPERATIVO',
            'physical_condition' => 'BUENO',
            'computer_code' => 'PC-TEST-001',
        ]);

        $child = \App\Models\Itam\Asset::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'parent_asset_id' => $parent->id,
            'status' => 'OPERATIVO',
            'physical_condition' => 'BUENO',
            'computer_code' => 'MOU-TEST-001',
            'serial_number' => 'SN-12345',
        ]);

        $candidate = \App\Models\Itam\Asset::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'parent_asset_id' => null,
            'status' => 'OPERATIVO',
            'physical_condition' => 'BUENO',
            'computer_code' => 'KEY-TEST-002',
            'serial_number' => 'SN-99999',
        ]);

        \Livewire\Livewire::test(Dashboard::class)
            ->call('openChildModal', $parent->id)
            ->assertSet('isChildModalOpen', true)
            ->assertSee('PC-TEST-001')
            ->assertSee('MOU-TEST-001')
            ->assertSee('SN-12345')
            ->assertSee('candidate-child-assets-datalist')
            // Probar resolución por datalist input
            ->set('selectedChildAssetInput', "[KEY-TEST-002] {$category->name}: {$brand->name} {$model->name} (S/N: SN-99999)")
            ->assertSet('selectedChildAssetId', $candidate->id)
            ->call('linkChildAsset')
            ->assertSee('Periférico KEY-TEST-002 vinculado')
            ->assertSet('selectedChildAssetInput', '')
            ->assertSet('selectedChildAssetId', null)
            ->call('unlinkChildAsset', $child->id)
            ->assertSee('Componente MOU-TEST-001 desvinculado.');

        $this->assertNull($child->fresh()->parent_asset_id);
        $this->assertEquals($parent->id, $candidate->fresh()->parent_asset_id);
    }
}
