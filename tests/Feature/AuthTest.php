<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ทดสอบ authenticate - success
    public function test_can_login_with_valid_credentials(): void
    {
        $admin = AdminUser::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => $admin->username,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Login Successfully');
    }

    // ทดสอบ authenticate - invalid credentials
    public function test_cannot_login_with_invalid_credentials(): void
    {
        AdminUser::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('correctpassword'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ authenticate - validation fail (missing fields)
    public function test_cannot_login_without_required_fields(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ authenticate - missing password
    public function test_cannot_login_without_password(): void
    {
        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
        ]);

        $response->assertStatus(422);
    }

    // ทดสอบ fetchAuth - authenticated
    public function test_can_fetch_authenticated_user(): void
    {
        $admin = AdminUser::factory()->create();

        $response = $this->actingAs($admin, 'web')->getJson('/api/auth');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Fetch Admin user success')
            ->assertJsonStructure([
                'data' => ['id', 'name', 'username', 'email'],
            ])
            ->assertJsonPath('data.id', $admin->id)
            ->assertJsonPath('data.username', $admin->username);
    }

    // ทดสอบ fetchAuth - unauthenticated
    public function test_cannot_fetch_auth_when_not_logged_in(): void
    {
        $response = $this->getJson('/api/auth');

        $response->assertStatus(401);
    }

    // ทดสอบ logout - success
    public function test_can_logout(): void
    {
        $admin = AdminUser::factory()->create();

        $response = $this->actingAs($admin, 'web')
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Log Out success')
            ->assertJsonPath('data', null);
    }

    // ทดสอบ logout - unauthenticated
    public function test_cannot_logout_when_not_logged_in(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
