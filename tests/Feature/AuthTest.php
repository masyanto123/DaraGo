<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Sign in');
        $response->assertSee('Please sign in to continue');
        $response->assertSee('Continue with Google');
    }

    public function test_superadmin_can_authenticate_using_the_login_screen(): void
    {
        $response = $this->post('/login', [
            'email' => 'superadmin@darago.id',
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_admin_can_authenticate_using_the_login_screen(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@darago.id',
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@darago.id',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::where('email', 'admin@darago.id')->first();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Selamat datang');
        $response->assertSee('Admin');
    }

    public function test_users_can_logout(): void
    {
        $user = User::where('email', 'admin@darago.id')->first();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}
