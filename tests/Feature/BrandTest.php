<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandTest extends TestCase
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
    public function test_can_fetch_brands(): void
    {
        Brand::factory()->count(3)->create();

        $response = $this->getJson('/api/brands');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'created_at', 'updated_at'],
                ],
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
    }

    // ทดสอบ index with search
    public function test_can_search_brands(): void
    {
        Brand::factory()->create(['name' => 'ASUS']);
        Brand::factory()->create(['name' => 'MSI']);

        $response = $this->getJson('/api/brands?search=ASUS');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $names = collect($response->json('data'))->pluck('name');
        $this->assertTrue($names->contains('ASUS'));
        $this->assertFalse($names->contains('MSI'));
    }

    // ทดสอบ index sort
    public function test_can_sort_brands(): void
    {
        Brand::factory()->create(['name' => 'Corsair']);
        Brand::factory()->create(['name' => 'ASUS']);

        $response = $this->getJson('/api/brands?sort_by=name&sort_order=asc');

        $response->assertStatus(200);

        $names = collect($response->json('data'))->pluck('name')->values()->toArray();
        $this->assertEquals(['ASUS', 'Corsair'], $names);
    }

    // ทดสอบ index validation fail
    public function test_cannot_fetch_brands_with_invalid_sort_by(): void
    {
        $response = $this->getJson('/api/brands?sort_by=invalid');

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ store
    public function test_can_create_brand(): void
    {
        $response = $this->postJson('/api/brands', [
            'name' => 'Kingston',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Kingston');

        $this->assertDatabaseHas('brands', ['name' => 'Kingston']);
    }

    // ทดสอบ store validation fail
    public function test_cannot_create_brand_without_name(): void
    {
        $response = $this->postJson('/api/brands', []);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ store name too long
    public function test_cannot_create_brand_with_name_exceeding_max_length(): void
    {
        $response = $this->postJson('/api/brands', [
            'name' => str_repeat('a', 256),
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ show
    public function test_can_fetch_single_brand(): void
    {
        $brand = Brand::factory()->create(['name' => 'G.Skill']);

        $response = $this->getJson("/api/brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $brand->id)
            ->assertJsonPath('data.name', 'G.Skill');
    }

    // ทดสอบ show 404
    public function test_returns_404_when_brand_not_found(): void
    {
        $response = $this->getJson('/api/brands/999');

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ update
    public function test_can_update_brand(): void
    {
        $brand = Brand::factory()->create(['name' => 'Corsair']);

        $response = $this->putJson("/api/brands/{$brand->id}", [
            'name' => 'Corsair Updated',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Corsair Updated');

        $this->assertDatabaseHas('brands', [
            'id' => $brand->id,
            'name' => 'Corsair Updated',
        ]);
    }

    // ทดสอบ update validation fail
    public function test_cannot_update_brand_without_name(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->putJson("/api/brands/{$brand->id}", []);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ update 404
    public function test_returns_404_when_updating_nonexistent_brand(): void
    {
        $response = $this->putJson('/api/brands/999', ['name' => 'Test']);

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ destroy
    public function test_can_delete_brand(): void
    {
        $brand = Brand::factory()->create(['name' => 'EVGA']);

        $response = $this->deleteJson("/api/brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    // ทดสอบ destroy 404
    public function test_returns_404_when_deleting_nonexistent_brand(): void
    {
        $response = $this->deleteJson('/api/brands/999');

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ unauthenticated
    public function test_unauthenticated_user_cannot_access_brands(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/brands');

        $response->assertStatus(401);
    }
}
