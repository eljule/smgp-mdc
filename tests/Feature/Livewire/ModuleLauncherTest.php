<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Enums\ModuleAccentColor;
use App\Enums\ModuleStatus;
use App\Livewire\Portal\ModuleLauncher;
use App\Models\CoreModule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ModuleLauncherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = \App\Models\User::firstOrCreate(
            ['username' => 'root'],
            [
                'full_name' => 'ADMINISTRADOR DEL SISTEMA SIGM',
                'email' => 'admin@castilla.gob.pe',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'is_active' => true,
            ]
        );
        $this->actingAs($admin);
    }

    public function test_unauthenticated_user_is_redirected_to_login_from_portal(): void
    {
        auth()->logout();
        $response = $this->get('/portal');
        $response->assertRedirect(route('login'));
    }

    public function test_renders_login_page_as_primary_home_route(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Entre a su cuenta');
        $response->assertSee('Municipalidad Distrital de Castilla');
        $response->assertSee('CUENTAS PARA PRUEBA DE PERMISOS');
    }

    public function test_renders_portal_launcher_page_successfully(): void
    {
        $response = $this->get('/portal');

        $response->assertStatus(200);
        $response->assertSee('SISTEMA INTEGRADO DE GESTIÓN MUNICIPAL');
        $response->assertSee('Subsistemas Autorizados');
    }

    public function test_renders_active_modules_and_supports_realtime_search(): void
    {
        CoreModule::create([
            'code' => 'central',
            'name' => 'Dashboard Central',
            'description' => 'Núcleo del sistema',
            'icon_url' => '/images/subsystem_central.png',
            'route' => '/admin',
            'accent_color' => ModuleAccentColor::Emerald,
            'status' => ModuleStatus::Active,
            'order' => 1,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        CoreModule::create([
            'code' => 'helpdesk',
            'name' => 'Soporte Técnico',
            'description' => 'Mesa de partes de incidencias',
            'icon_url' => '/images/subsystem_helpdesk.png',
            'route' => '/helpdesk',
            'accent_color' => ModuleAccentColor::Purple,
            'status' => ModuleStatus::Active,
            'order' => 2,
            'is_active' => true,
            'requires_auth' => true,
            'secondary_action' => [
                'label' => 'Generar Ticket de Soporte',
                'route' => '/soporte',
            ],
        ]);

        Livewire::test(ModuleLauncher::class)
            ->assertSee('Dashboard Central')
            ->assertSee('Soporte Técnico')
            ->assertSee('Generar Ticket de Soporte')
            ->set('search', 'Central')
            ->assertSee('Dashboard Central')
            ->assertDontSee('Soporte Técnico');
    }

    public function test_filters_modules_by_active_user_role(): void
    {
        CoreModule::create([
            'code' => 'central',
            'name' => 'Dashboard Central',
            'description' => 'Núcleo del sistema',
            'icon_url' => '/images/subsystem_central.png',
            'route' => '/admin',
            'accent_color' => ModuleAccentColor::Emerald,
            'status' => ModuleStatus::Active,
            'order' => 1,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        CoreModule::create([
            'code' => 'transportes',
            'name' => 'Licencias de Transportes',
            'description' => 'Trámites de transportes',
            'icon_url' => '/images/subsystem_transportes.png',
            'route' => '/transportes',
            'accent_color' => ModuleAccentColor::Amber,
            'status' => ModuleStatus::Maintenance,
            'order' => 2,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        CoreModule::create([
            'code' => 'itam',
            'name' => 'Inventario TI',
            'description' => 'Gestión de hardware y software',
            'icon_url' => '/images/subsystem_itam.png',
            'route' => '/itam',
            'accent_color' => ModuleAccentColor::Blue,
            'status' => ModuleStatus::Active,
            'order' => 3,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        CoreModule::create([
            'code' => 'helpdesk',
            'name' => 'Soporte Técnico',
            'description' => 'Mesa de partes de incidencias',
            'icon_url' => '/images/subsystem_helpdesk.png',
            'route' => '/helpdesk',
            'accent_color' => ModuleAccentColor::Purple,
            'status' => ModuleStatus::Active,
            'order' => 4,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        // jperez solo ve 3 módulos (no ve transportes)
        Livewire::test(ModuleLauncher::class, ['activeUser' => 'jperez'])
            ->assertSee('Dashboard Central')
            ->assertSee('Inventario TI')
            ->assertSee('Soporte Técnico')
            ->assertDontSee('Licencias de Transportes');

        // mgomez solo ve 2 módulos (central y helpdesk)
        Livewire::test(ModuleLauncher::class, ['activeUser' => 'mgomez'])
            ->assertSee('Dashboard Central')
            ->assertSee('Soporte Técnico')
            ->assertDontSee('Inventario TI')
            ->assertDontSee('Licencias de Transportes');
    }

    public function test_renders_maintenance_page_with_own_header_and_without_regular_header(): void
    {
        CoreModule::create([
            'code' => 'transportes',
            'name' => 'Licencias de Transportes',
            'description' => 'Trámites de transportes',
            'icon_url' => '/images/subsystem_transportes.png',
            'route' => '/transportes',
            'accent_color' => ModuleAccentColor::Amber,
            'status' => ModuleStatus::Maintenance,
            'order' => 2,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        $response = $this->get('/mantenimiento?code=transportes');

        $response->assertStatus(200);
        $response->assertSee('SIGM');
        $response->assertSee('Modo Mantenimiento');
        $response->assertSee('Licencias de Transportes');
        $response->assertSee('Panel de Subsistemas');
        // Asegurar que NO se renderiza la cabecera regular del panel general
        $response->assertDontSee('ADMINISTRADOR DEL SISTEMA SIGM');
        $response->assertDontSee('Cerrar Sesión');
    }
}
