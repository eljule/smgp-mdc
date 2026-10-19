<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CoreModuleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CoreModuleSeeder::class);
        $this->seed(UserSeeder::class);
    }

    public function test_login_authenticates_user_and_redirects_cleanly_to_portal(): void
    {
        $response = $this->post('/login', [
            'username' => 'soporte1',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/portal');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertNotNull($user);
        $this->assertSame('soporte1', $user->username);
        $this->assertSame('SO', $user->initials);
        $this->assertSame('JAIME HENRY SANDOVAL NIEVES', $user->display_title);
        $this->assertSame('Técnico de Soporte e ITAM', $user->display_role);
    }

    public function test_login_with_demo_password_works(): void
    {
        $response = $this->post('/login', [
            'username' => 'root',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/portal');
        $this->assertAuthenticated();
        $this->assertSame('root', auth()->user()->username);
    }

    public function test_login_with_invalid_credentials_fails(): void
    {
        $response = $this->post('/login', [
            'username' => 'soporte1',
            'password' => 'wrong_pass',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_portal_renders_authenticated_user_in_navbar_header(): void
    {
        $user = User::where('username', 'soporte1')->first();
        $this->actingAs($user);

        $response = $this->get('/portal');

        $response->assertStatus(200);
        // Debe mostrar las iniciales y rol del usuario autenticado en el header
        $response->assertSee('SO');
        $response->assertSee('JAIME HENRY SANDOVAL NIEVES');
        $response->assertSee('Técnico de Soporte e ITAM');
        // No debe mostrar las credenciales en la URL ni el administrador si es jperez
        $response->assertDontSee('user=soporte1');
        $response->assertDontSee('password=');
    }

    public function test_subsystem_header_renders_authenticated_user_profile(): void
    {
        $user = User::where('username', 'soporte1')->first();
        $this->actingAs($user);

        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('SO');
        $response->assertSee('JAIME HENRY SANDOVAL NIEVES');
        $response->assertSee('Técnico de Soporte e ITAM');
        $response->assertSee('Cerrar Sesión');
    }

    public function test_logout_clears_session_and_redirects_to_login(): void
    {
        $user = User::where('username', 'soporte1')->first();
        $this->actingAs($user);

        $response = $this->get('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_login_page_renders_helpdesk_tab_and_dni_lookup_endpoint(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Acceso Módulos');
        $response->assertSee('Mesa de Ayuda');
        $response->assertSee('DNI del Usuario / Servidor Municipal');
        $response->assertSee('Imprimir QR');

        // Test DNI lookup for known catalog person
        $dniLookupResponse = $this->getJson('/api/helpdesk/dni-lookup?dni=02821801');
        $dniLookupResponse->assertStatus(200);
        $dniLookupResponse->assertJson([
            'found' => true,
            'person' => [
                'dni' => '02821801',
                'name' => 'CARLOS EDUARDO ROJAS MATIAS',
            ],
        ]);
        $this->assertStringContainsString('soporte/nuevo-ticket', $dniLookupResponse->json('ticket_url'));
        $this->assertStringContainsString('create-qr-code', $dniLookupResponse->json('qr_url'));

        // Test DNI lookup for nonexistent DNI
        $nonexistentResponse = $this->getJson('/api/helpdesk/dni-lookup?dni=00000000');
        $nonexistentResponse->assertStatus(200);
        $nonexistentResponse->assertJson([
            'found' => false,
        ]);

        // Test office QR endpoint
        $officeQrResponse = $this->getJson('/api/helpdesk/office-qr?office=ALCALDÍA');
        $officeQrResponse->assertStatus(200);
        $officeQrResponse->assertJson([
            'found' => true,
            'office' => 'ALCALDÍA',
        ]);
        $this->assertStringContainsString('soporte/nuevo-ticket', $officeQrResponse->json('ticket_url'));
    }
}
