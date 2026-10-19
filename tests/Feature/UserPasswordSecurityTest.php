<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Dashboard;
use App\Models\User;
use Database\Seeders\CoreModuleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserPasswordSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CoreModuleSeeder::class);
        $this->seed(UserSeeder::class);
    }

    public function test_user_with_provisional_password_is_redirected_to_change_password(): void
    {
        $user = User::where('username', 'soporte1')->firstOrFail();
        $user->must_change_password = true;
        $user->save();

        $response = $this->post('/login', [
            'username' => 'soporte1',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('password.change'));
        $this->assertAuthenticated();

        // Trying to access portal redirects to password change
        $portalResponse = $this->get('/portal');
        $portalResponse->assertRedirect(route('password.change'));
    }

    public function test_user_can_successfully_change_password_and_access_portal(): void
    {
        $user = User::where('username', 'soporte1')->firstOrFail();
        $user->must_change_password = true;
        $user->save();

        $this->actingAs($user);

        $response = $this->post('/cambiar-password', [
            'current_password' => 'admin123',
            'new_password' => 'CastillaNueva2026!',
            'new_password_confirmation' => 'CastillaNueva2026!',
        ]);

        $response->assertRedirect('/portal');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertFalse($user->must_change_password);
        $this->assertTrue(Hash::check('CastillaNueva2026!', $user->password));

        // Now accessing portal is allowed
        $this->get('/portal')->assertOk();
    }

    public function test_failed_login_increments_counter_and_locks_after_5_attempts(): void
    {
        $user = User::where('username', 'soporte1')->firstOrFail();
        $this->assertSame(0, $user->failed_login_attempts);
        $this->assertFalse($user->is_locked);

        // Attempts 1 to 4
        for ($i = 1; $i <= 4; $i++) {
            $resp = $this->post('/login', [
                'username' => 'soporte1',
                'password' => 'wrongpassword',
            ]);
            $resp->assertSessionHas('error');
            $this->assertGuest();
        }

        $user->refresh();
        $this->assertSame(4, $user->failed_login_attempts);
        $this->assertFalse($user->is_locked);

        // 5th attempt locks the account
        $resp5 = $this->post('/login', [
            'username' => 'soporte1',
            'password' => 'wrongpassword',
        ]);
        $resp5->assertSessionHas('error');
        $this->assertGuest();

        $user->refresh();
        $this->assertSame(5, $user->failed_login_attempts);
        $this->assertTrue($user->is_locked);

        // Even with correct password, login is blocked
        $blockedResp = $this->post('/login', [
            'username' => 'soporte1',
            'password' => 'admin123',
        ]);
        $blockedResp->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_admin_can_unlock_user_account(): void
    {
        $user = User::where('username', 'soporte1')->firstOrFail();
        $user->is_locked = true;
        $user->failed_login_attempts = 5;
        $user->save();

        Livewire::test(Dashboard::class)
            ->call('unlockUser', $user->id);

        $user->refresh();
        $this->assertFalse($user->is_locked);
        $this->assertSame(0, $user->failed_login_attempts);
    }

    public function test_admin_can_reset_user_password(): void
    {
        $user = User::where('username', 'soporte1')->firstOrFail();
        $user->is_locked = true;
        $user->failed_login_attempts = 5;
        $user->must_change_password = false;
        $user->save();

        Livewire::test(Dashboard::class)
            ->call('openResetPassword', $user->id)
            ->assertSet('showResetPasswordModal', true)
            ->assertSet('resetUsername', 'soporte1')
            ->set('resetNewPassword', 'Temporal2026!')
            ->call('executeResetPassword')
            ->assertSet('showResetPasswordModal', false);

        $user->refresh();
        $this->assertTrue(Hash::check('Temporal2026!', $user->password));
        $this->assertTrue($user->must_change_password);
        $this->assertFalse($user->is_locked);
        $this->assertSame(0, $user->failed_login_attempts);
    }

    public function test_newly_created_user_has_must_change_password_flag(): void
    {
        Livewire::test(Dashboard::class)
            ->set('userFormUsername', 'rgarcia')
            ->set('userFormFullName', 'Roberto Garcia Morales')
            ->set('userFormPassword', 'Provisional123')
            ->set('userFormRole', 'Técnico de Soporte e ITAM')
            ->set('userFormModules', ['itam', 'helpdesk'])
            ->call('saveUser');

        $user = User::where('username', 'rgarcia')->firstOrFail();
        $this->assertTrue($user->must_change_password);
        $this->assertFalse($user->is_locked);
        $this->assertSame(0, $user->failed_login_attempts);
    }
}
