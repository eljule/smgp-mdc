<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Offices as OfficesComponent;
use App\Livewire\Admin\People as PeopleComponent;
use App\Livewire\Admin\PermissionMatrix as PermissionMatrixComponent;
use App\Livewire\Admin\Permissions as PermissionsComponent;
use App\Livewire\Admin\Roles as RolesComponent;
use App\Livewire\Admin\Subsystems as SubsystemsComponent;
use App\Livewire\Admin\Users as UsersComponent;
use App\Models\Office;
use App\Models\Person;
use App\Models\Sede;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_admin_dashboard_page_successfully(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('SIGM');
        $response->assertSee('Panel de Administración');
        $response->assertSee('Resumen del Ecosistema SIGM');
        $response->assertSee('Control centralizado de identidades');
        $response->assertSee('Personas en Base de Datos');
        $response->assertSee('Personal Municipal');
        $response->assertSee('Ciudadanos Administrados');
        $response->assertSee('Cuentas de Acceso');
        $response->assertSee('Subsistemas Integrados al SIGM');
        $response->assertSee('4 de 4 Activos');
        $response->assertSee('+ Registrar Persona');
        $response->assertSee('+ Nuevo Usuario & Permisos', false);
    }

    public function test_renders_personas_table_page_successfully(): void
    {
        $response = $this->get('/admin/personas');

        $response->assertStatus(200);
        $response->assertSee('Tabla Maestra de Personas');
        $response->assertSee('46 registros');
        $response->assertSee('CARLOS EDUARDO ROJAS MATIAS');
        $response->assertSee('02821801');
        $response->assertSee('PROCURADURADOR PUBLICA MUNICIPAL');
        $response->assertSee('EMMA FLOR SAAVEDRA PACHERREZ');
        $response->assertSee('DORIS LOPEZ GARCIA');
        $response->assertSee('Mostrando');
        $response->assertSee('de');
        $response->assertSee('46');
        $response->assertSee('personas');
    }

    public function test_people_component_cease_modal_flow(): void
    {
        $person = Person::create([
            'type' => 'PERSONAL',
            'document_type' => 'DNI',
            'document_number' => '40125896',
            'first_name' => 'PEDRO JOSÉ',
            'paternal_surname' => 'ALMENDRAS',
            'maternal_surname' => 'FLORES',
            'position' => 'ESPECIALISTA EN CATASTRO URBANO',
            'office' => '[GDU] GERENCIA DE DESARROLLO URBANO',
            'labor_condition' => 'CAS',
            'labor_status' => 'ACTIVO',
            'is_active' => true,
        ]);

        Livewire::test(PeopleComponent::class)
            ->call('openCeaseModal', $person->id)
            ->assertSet('showCeaseModal', true)
            ->assertSet('ceaseReason', 'RENUNCIA VOLUNTARIA')
            ->assertSee('COLABORADOR MUNICIPAL A CESAR')
            ->assertSee('PEDRO JOSÉ ALMENDRAS FLORES')
            ->assertSee('Registro en Base de Datos')
            ->assertSee('Cuenta de Acceso SIGM')
            ->assertSee('Custodia de Activos Tecnológicos (ITAM)')
            ->assertSee('Confirmar Cese y Entrega de Cargo')
            ->call('saveCease')
            ->assertSet('showCeaseModal', false)
            ->assertSee('Cese laboral registrado para PEDRO JOSÉ ALMENDRAS FLORES');

        $person->refresh();
        $this->assertEquals('CESADO', $person->labor_status);
        $this->assertFalse($person->is_active);
    }

    public function test_people_component_filters_and_searches(): void
    {
        Livewire::test(PeopleComponent::class)
            ->assertSee('CARLOS EDUARDO ROJAS MATIAS')
            ->assertSee('02821801')
            ->set('search', 'ROJAS MATIAS')
            ->assertSee('CARLOS EDUARDO ROJAS MATIAS')
            ->assertDontSee('EMMA FLOR SAAVEDRA PACHERREZ')
            ->set('search', '')
            ->call('gotoPage', 2)
            ->assertSee('JAIME HENRRY SANDOVAL NIEVES')
            ->assertDontSee('CARLOS EDUARDO ROJAS MATIAS');
    }

    public function test_renders_users_table_page_successfully(): void
    {
        $response = $this->get('/admin/usuarios');

        $response->assertStatus(200);
        $response->assertSee('Gestión Intuitiva de Usuarios y Permisos');
        $response->assertSee('Nuevo Usuario con Permisos');
        $response->assertSee('Cuentas de Usuario Activas (49)');
        $response->assertSee('Administrador Central');
        $response->assertSee('Técnico de Soporte e ITAM');
        $response->assertSee('Operador de Transportes');
        $response->assertSee('Personal General / Reportante');
        $response->assertSee('root');
        $response->assertSee('ADMINISTRADOR DEL SISTEMA SIGM');
        $response->assertSee('admin@castilla.gob.pe');
        $response->assertSee('Todos los subsistemas (Acceso Total)');
        $response->assertSee('10783864');
        $response->assertSee('JAIME HENRY SANDOVAL NIEVES');
        $response->assertSee('soporte1');
        $response->assertSee('CARLOS EDUARDO ROJAS MATIAS');
        $response->assertSee('Editar Permisos');
    }

    public function test_users_component_filters_and_searches(): void
    {
        Livewire::test(UsersComponent::class)
            ->assertSee('root')
            ->assertSee('ADMINISTRADOR DEL SISTEMA SIGM')
            ->set('search', 'root')
            ->assertSee('root')
            ->assertDontSee('JAIME HENRY SANDOVAL NIEVES')
            // Búsqueda inteligente por DNI
            ->set('search', '10783864')
            ->assertSee('JAIME HENRY SANDOVAL NIEVES')
            ->assertDontSee('CARLOS EDUARDO ROJAS MATIAS')
            // Búsqueda inteligente por nombre (case y acento insensible)
            ->set('search', 'sandoval')
            ->assertSee('JAIME HENRY SANDOVAL NIEVES')
            ->assertDontSee('CARLOS EDUARDO ROJAS MATIAS')
            ->call('clearSearch')
            ->assertSet('search', '')
            ->call('setRoleFilter', 'Operador de Transportes')
            ->assertSee('OPERADOR DE TRANSPORTES')
            ->assertDontSee('root')
            ->set('roleFilter', 'TODOS')
            ->call('openEditPermissions', 1)
            ->assertSet('showEditModal', true)
            ->assertSet('selectedUserId', 1)
            ->call('savePermissions')
            ->assertSet('showEditModal', false)
            ->assertSee('Permisos actualizados correctamente');
    }

    public function test_renders_roles_page_successfully(): void
    {
        $response = $this->get('/admin/roles');

        $response->assertStatus(200);
        $response->assertSee('Mantenimiento de Roles Institucionales');
        $response->assertSee('4 roles');
        $response->assertSee('Nuevo Rol Institucional');
        $response->assertSee('TOTAL ROLES REGISTRADOS');
        $response->assertSee('ROLES BASE DEL SISTEMA');
        $response->assertSee('ROLES PERSONALIZADOS');
        $response->assertSee('Listado de Roles (4)');
        $response->assertSee('Administrador Central');
        $response->assertSee('admin_central');
        $response->assertSee('Multisubsistema (Global)');
        $response->assertSee('Operador de Transportes');
        $response->assertSee('transport_operator');
        $response->assertSee('Licencias de Transporte');
        $response->assertSee('Personal General / Reportante');
        $response->assertSee('Técnico de Soporte e ITAM');
        $response->assertSee('Permisos');
        $response->assertSee(route('admin.matrix', ['rol' => 'transport_operator']));
    }

    public function test_roles_component_filters_and_searches(): void
    {
        Livewire::test(RolesComponent::class)
            ->assertSee('Administrador Central')
            ->assertSee('admin_central')
            ->set('search', 'transport')
            ->assertSee('Operador de Transportes')
            ->assertDontSee('Administrador Central')
            ->set('search', '')
            ->call('setScopeFilter', 'itam')
            ->assertSee('Técnico de Soporte e ITAM')
            ->assertDontSee('Operador de Transportes')
            ->set('scopeFilter', 'TODOS')
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->call('saveRole')
            ->assertSet('showCreateModal', false)
            ->assertSee('Rol institucional guardado correctamente')
            ->call('goToMatrix', 'transport_operator')
            ->assertRedirect(route('admin.matrix', ['rol' => 'transport_operator']));
    }

    public function test_renders_permissions_page_successfully(): void
    {
        $response = $this->get('/admin/permisos');

        $response->assertStatus(200);
        $response->assertSee('Catálogo de Permisos Granulares');
        $response->assertSee('19 permisos');
        $response->assertSee('Dashboard Central y Configuración');
        $response->assertSee('subsistema: central_dashboard');
        $response->assertSee('admin.people.manage');
        $response->assertSee('Registrar y Actualizar Personas');
        $response->assertSee('Soporte Técnico y Helpdesk');
        $response->assertSee('helpdesk.kb.manage');
        $response->assertSee('Inventario y Gestión TI (ITAM)');
        $response->assertSee('itam.assets.manage');
        $response->assertSee('Licencias de Transportes');
        $response->assertSee('transport.licenses.issue');
        $response->assertSee('Activo en SIGM');
    }

    public function test_permissions_component_filters_and_searches(): void
    {
        Livewire::test(PermissionsComponent::class)
            ->assertSee('admin.people.manage')
            ->assertSee('helpdesk.tickets.create')
            ->set('search', 'people.manage')
            ->assertSee('admin.people.manage')
            ->assertDontSee('helpdesk.tickets.create')
            ->set('search', '')
            ->call('setSubsystemFilter', 'helpdesk_support')
            ->assertSee('helpdesk.tickets.create')
            ->assertDontSee('admin.people.manage');
    }

    public function test_renders_permission_matrix_page_successfully(): void
    {
        $response = $this->get('/admin/asignar-permisos');

        $response->assertStatus(200);
        $response->assertSee('Matriz de Asignación de Permisos por Rol');
        $response->assertSee('Control Granular');
        $response->assertSee('Guardar Permisos del Rol');
        $response->assertSee('SELECCIONE EL ROL INSTITUCIONAL A CONFIGURAR:');
        $response->assertSee('Administrador Central');
        $response->assertSee('admin_central');
        $response->assertSee('19 / 19 permisos');
        $response->assertSee('Marcar Todos');
        $response->assertSee('Desmarcar Todos');
        $response->assertSee('Todo el módulo');
        $response->assertSee('HABILITADO');
        $response->assertSee('admin.people.manage');
    }

    public function test_permission_matrix_interactive_toggles_and_batch_actions(): void
    {
        Livewire::test(PermissionMatrixComponent::class)
            ->assertSee('19 / 19 permisos')
            ->call('selectRole', 'municipal_staff')
            ->assertSee('2 / 19 permisos')
            ->call('togglePermission', 'admin.people.manage')
            ->assertSee('3 / 19 permisos')
            ->call('togglePermission', 'admin.people.manage')
            ->assertSee('2 / 19 permisos')
            ->call('saveMatrix')
            ->assertSee('Permisos del rol actualizados correctamente');
    }

    public function test_livewire_admin_dashboard_component_renders_metrics(): void
    {
        Livewire::test(AdminDashboard::class)
            ->assertSee('Resumen del Ecosistema SIGM')
            ->assertSee('46')
            ->assertSee('49')
            ->assertSee('Dashboard Central y Configuración')
            ->assertSee('Licencias de Transportes')
            ->assertSee('Inventario y Gestión TI')
            ->assertSee('Soporte Técnico y Helpdesk');
    }

    public function test_renders_all_subsystems_using_shared_layout(): void
    {
        $this->get('/transportes')
            ->assertStatus(200)
            ->assertSee('Licencias de Transportes');

        $this->get('/itam')
            ->assertStatus(200)
            ->assertSee('Inventario y Gestión TI');

        $this->get('/helpdesk')
            ->assertStatus(200)
            ->assertSee('Soporte Técnico y Helpdesk');
    }

    public function test_renders_offices_page_successfully(): void
    {
        $response = $this->get('/admin/oficinas');

        $response->assertStatus(200);
        $response->assertSee('Estructura Orgánica y Dependencias');
        $response->assertSee('Total Dependencias');
        $response->assertSee('66');
        $response->assertSee('Sedes Municipales');
        $response->assertSee('10');
        $response->assertSee('Alta Dirección y Gerencias');
        $response->assertSee('11');
        $response->assertSee('Subgerencias y Unidades');
        $response->assertSee('55');
        $response->assertSee('OFICINA DE REGIDORES');
        $response->assertSee('ALCALDÍA');
        $response->assertSee('GERENCIA MUNICIPAL');
        $response->assertSee('PALACIO MUNICIPAL (SEDE PRINCIPAL)');
    }

    public function test_offices_component_filters_and_actions(): void
    {
        Livewire::test(OfficesComponent::class)
            ->assertSee('OFICINA DE REGIDORES')
            ->set('search', 'REGIDORES')
            ->assertSee('OFICINA DE REGIDORES')
            ->assertDontSee('ALCALDÍA')
            ->set('search', '')
            ->set('levelFilter', '1')
            ->assertSee('Nivel 1')
            ->call('setViewMode', 'chart')
            ->assertSee('Organigrama Institucional de Castilla')
            ->call('setViewMode', 'table')
            ->assertSee('CÓDIGO')
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true);
    }

    public function test_renders_subsystems_page_successfully(): void
    {
        $this->seed();
        $response = $this->get('/admin/subsistemas');

        $response->assertStatus(200);
        $response->assertSee('Catálogo de Subsistemas SIGM');
        $response->assertSee('Total: 4');
        $response->assertSee('Activos: 3');
        $response->assertSee('Mantenimiento: 1');
        $response->assertSee('CENTRAL_DASHBOARD');
        $response->assertSee('TRANSPORT_LICENSES');
        $response->assertSee('IT_INVENTORY');
        $response->assertSee('HELPDESK_SUPPORT');
        $response->assertSee('MANTENIMIENTO');
    }

    public function test_subsystems_component_filters_and_maintenance_toggle(): void
    {
        $this->seed();
        Livewire::test(SubsystemsComponent::class)
            ->assertSee('CENTRAL_DASHBOARD')
            ->set('filter', 'operativos')
            ->assertSee('CENTRAL_DASHBOARD')
            ->assertDontSee('TRANSPORT_LICENSES')
            ->set('filter', 'mantenimiento')
            ->assertSee('TRANSPORT_LICENSES')
            ->assertDontSee('CENTRAL_DASHBOARD')
            ->set('filter', 'todos')
            ->call('toggleMaintenance', 1)
            ->assertSee('ha entrado en modo MANTENIMIENTO')
            ->call('toggleMaintenance', 1)
            ->assertSee('ahora está OPERATIVO')
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true);
    }

    public function test_offices_component_sede_crud_and_color_management(): void
    {
        Livewire::test(OfficesComponent::class)
            ->call('openSedesModal')
            ->assertSet('showSedesModal', true)
            ->call('openCreateSedeForm')
            ->assertSet('showSedeForm', true)
            ->set('sedeFormName', 'NUEVA SEDE PARQUE ZONAL')
            ->set('sedeFormCode', 'P_ZONAL')
            ->set('sedeFormAddress', 'Sector Primavera')
            ->set('sedeFormColor', 'orange')
            ->call('saveSede')
            ->assertSet('showSedeForm', false);

        $this->assertDatabaseHas('core_sedes', [
            'name' => 'NUEVA SEDE PARQUE ZONAL',
            'color' => 'orange',
        ]);

        $sede = Sede::where('name', 'NUEVA SEDE PARQUE ZONAL')->first();
        $this->assertNotNull($sede);

        Livewire::test(OfficesComponent::class)
            ->call('openEditSedeForm', $sede->id)
            ->assertSet('selectedSedeId', $sede->id)
            ->assertSet('sedeFormName', 'NUEVA SEDE PARQUE ZONAL')
            ->set('sedeFormColor', 'rose')
            ->call('saveSede')
            ->assertSet('showSedeForm', false);

        $this->assertEquals('rose', $sede->fresh()->color);

        Livewire::test(OfficesComponent::class)
            ->call('deleteSede', $sede->id);

        $this->assertDatabaseMissing('core_sedes', [
            'id' => $sede->id,
        ]);
    }

    public function test_offices_component_save_and_parent_assignment(): void
    {
        $this->seed();

        $alcaldia = Office::where('code', '02')->first();
        $oci = Office::where('code', '10')->first();

        $this->assertNotNull($alcaldia);
        $this->assertNotNull($oci);

        // 1. Edit OCI and set parent to Alcaldía
        Livewire::test(OfficesComponent::class)
            ->call('openEditModal', $oci->id)
            ->assertSet('showEditModal', true)
            ->assertSet('selectedOfficeId', $oci->id)
            ->set('formParentId', $alcaldia->id)
            ->call('saveOffice')
            ->assertSet('showEditModal', false);

        $this->assertDatabaseHas('core_offices', [
            'id' => $oci->id,
            'parent_id' => $alcaldia->id,
            'parent_name' => $alcaldia->name,
            'level' => 2,
        ]);

        // 2. Edit OCI and remove parent (set to null / empty string)
        Livewire::test(OfficesComponent::class)
            ->call('openEditModal', $oci->id)
            ->set('formParentId', '')
            ->call('saveOffice')
            ->assertSet('showEditModal', false);

        $this->assertDatabaseHas('core_offices', [
            'id' => $oci->id,
            'parent_id' => null,
            'parent_name' => null,
            'level' => 1,
        ]);

        // 3. Register a new office with a parent
        Livewire::test(OfficesComponent::class)
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->set('formCode', '02.99')
            ->set('formSigla', 'OTEST')
            ->set('formName', 'OFICINA DE PRUEBA ORGANICA')
            ->set('formParentId', $alcaldia->id)
            ->set('formSede', 'PALACIO MUNICIPAL (SEDE PRINCIPAL)')
            ->call('saveOffice')
            ->assertSet('showCreateModal', false)
            ->assertSee('Dependencia \'OFICINA DE PRUEBA ORGANICA\' registrada con éxito');

        $created = Office::where('code', '02.99')->first();
        $this->assertNotNull($created);
        $this->assertEquals($alcaldia->id, $created->parent_id);
        $this->assertEquals($alcaldia->name, $created->parent_name);
        $this->assertEquals(2, $created->level);

        // 4. Delete the test office
        Livewire::test(OfficesComponent::class)
            ->call('deleteOffice', $created->id);

        $this->assertDatabaseMissing('core_offices', [
            'id' => $created->id,
        ]);
    }
}
