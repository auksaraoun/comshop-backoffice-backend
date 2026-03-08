<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    private AdminUser $authUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authUser = AdminUser::factory()->create();
        $this->actingAs($this->authUser, 'web');
    }

    // ทดสอบ index
    public function test_can_fetch_admin_users(): void
    {
        AdminUser::factory()->count(3)->create();

        $response = $this->getJson('/api/admin-users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'username', 'email']
                ],
                'meta' => ['current_page', 'per_page', 'total', 'last_page']
            ]);
    }

    // ทดสอบ store
    public function test_can_create_admin_user(): void
    {
        $payload = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/admin-users', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.username', 'johndoe');

        $this->assertDatabaseHas('admin_users', [
            'username' => 'johndoe',
            'email' => 'john@example.com',
        ]);
    }

    // ทดสอบ store validation fail
    public function test_cannot_create_admin_user_with_duplicate_email(): void
    {
        AdminUser::factory()->create(['email' => 'john@example.com']);

        $payload = [
            'name' => 'John Doe',
            'username' => 'johndoe2',
            'email' => 'john@example.com', // ซ้ำ
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/admin-users', $payload);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ show
    public function test_can_fetch_single_admin_user(): void
    {
        $adminUser = AdminUser::factory()->create();

        $response = $this->getJson("/api/admin-users/{$adminUser->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $adminUser->id);
    }

    // ทดสอบ show 404
    public function test_returns_404_when_admin_user_not_found(): void
    {
        $response = $this->getJson('/api/admin-users/999');

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ update
    public function test_can_update_admin_user(): void
    {
        $adminUser = AdminUser::factory()->create();

        $response = $this->patchJson("/api/admin-users/{$adminUser->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Name');
    }

    // ทดสอบ update password
    public function test_can_update_admin_user_password(): void
    {
        $adminUser = AdminUser::factory()->create();

        $response = $this->patchJson("/api/admin-users/{$adminUser->id}/password", [
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    // ทดสอบ destroy
    public function test_can_delete_admin_user(): void
    {
        $adminUser = AdminUser::factory()->create();

        $response = $this->deleteJson("/api/admin-users/{$adminUser->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('admin_users', [
            'id' => $adminUser->id,
        ]);
    }
}
