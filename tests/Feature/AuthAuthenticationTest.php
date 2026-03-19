<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $this->get('/admin/products')
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_login_and_access_admin_products(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@login.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->post('login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('admin.products.index'))
            ->assertOk();
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@invalid.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->post('login', [
            'email' => 'admin@invalid.com',
            'password' => 'wrong-password',
        ])
            ->assertSessionHasErrors('email');
    }
}
