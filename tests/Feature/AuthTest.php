<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_login_dapat_dibuka(): void
    {
        $this->get(route('auth.login'))->assertOk();
    }

    public function test_admin_dapat_login_dengan_kredensial_benar(): void
    {
        $user = User::factory()->create();

        $this->post(route('auth.login.submit'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        $user = User::factory()->create();

        $this->post(route('auth.login.submit'), [
            'email' => $user->email,
            'password' => 'salah-password',
        ])->assertSessionHasErrors('login_error');

        $this->assertGuest();
    }

    public function test_login_dikunci_setelah_tiga_kali_gagal(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 3) as $i) {
            $this->post(route('auth.login.submit'), ['email' => $user->email, 'password' => 'salah']);
        }

        // Percobaan keempat ditolak walaupun password benar.
        $this->post(route('auth.login.submit'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('login_error');

        $this->assertGuest();
    }

    public function test_tamu_tidak_dapat_membuka_halaman_admin(): void
    {
        foreach (['admin.dashboard', 'manage-user', 'manage-laporan'] as $route) {
            $this->get(route($route))->assertRedirect(route('auth.login'));
        }
    }

    public function test_admin_dapat_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('auth.login'));

        $this->assertGuest();
    }
}
