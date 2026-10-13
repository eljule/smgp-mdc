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
            'username' => 'jperez',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/portal');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertNotNull($user);
        $this->assertSame('jperez', $user->username);
        $this->assertSame('JP', $user->initials);
        $this->assertSame('JUAN ALBERTO PÉREZ MORALES', $user->display_title);
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
            'username' => 'jperez',
            'password' => 'wrong_pass',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_portal_renders_authenticated_user_in_navbar_header(): void
    {
        $user = User::where('username', 'jperez')->first();
        $this->actingAs($user);

        $response = $this->get('/portal');

        $response->assertStatus(200);
        // Debe mostrar las iniciales y rol del usuario autenticado en el header
        $response->assertSee('JP');
        $response->assertSee('JUAN ALBERTO PÉREZ MORALES');
        $response->assertSee('Técnico de Soporte e ITAM');
        // No debe mostrar las credenciales en la URL ni el administrador si es jperez
        $response->assertDontSee('user=jperez');
        $response->assertDontSee('password=');
    }

    public function test_subsystem_header_renders_authenticated_user_profile(): void
    {
        $user = User::where('username', 'jperez')->first();
        $this->actingAs($user);

        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('JP');
        $response->assertSee('JUAN ALBERTO PÉREZ MORALES');
        $response->assertSee('Técnico de Soporte e ITAM');
        $response->assertSee('Cerrar Sesión');
    }

    public function test_logout_clears_session_and_redirects_to_login(): void
    {
        $user = User::where('username', 'jperez')->first();
        $this->actingAs($user);

        $response = $this->get('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
