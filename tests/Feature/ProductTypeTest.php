<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\ProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTypeTest extends TestCase
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
    public function test_can_fetch_product_types(): void
    {
        ProductType::factory()->count(3)->create();

        $response = $this->getJson('/api/product-types');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'admin_id', 'created_at', 'updated_at'],
                ],
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
    }

    // ทดสอบ index with search
    public function test_can_search_product_types(): void
    {
        ProductType::factory()->create(['name' => 'GPU']);
        ProductType::factory()->create(['name' => 'CPU']);

        $response = $this->getJson('/api/product-types?search=GPU');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $names = collect($response->json('data'))->pluck('name');
        $this->assertTrue($names->contains('GPU'));
        $this->assertFalse($names->contains('CPU'));
    }

    // ทดสอบ store
    public function test_can_create_product_type(): void
    {
        $response = $this->postJson('/api/product-types', [
            'name' => 'Motherboard',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Motherboard')
            ->assertJsonPath('data.admin_id', $this->authUser->id);

        $this->assertDatabaseHas('product_types', [
            'name' => 'Motherboard',
            'admin_id' => $this->authUser->id,
        ]);
    }

    // ทดสอบ store validation fail
    public function test_cannot_create_product_type_without_name(): void
    {
        $response = $this->postJson('/api/product-types', []);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ store name too long
    public function test_cannot_create_product_type_with_name_exceeding_max_length(): void
    {
        $response = $this->postJson('/api/product-types', [
            'name' => str_repeat('a', 256),
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ show
    public function test_can_fetch_single_product_type(): void
    {
        $productType = ProductType::factory()->create(['name' => 'SSD']);

        $response = $this->getJson("/api/product-types/{$productType->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $productType->id)
            ->assertJsonPath('data.name', 'SSD');
    }

    // ทดสอบ show 404
    public function test_returns_404_when_product_type_not_found(): void
    {
        $response = $this->getJson('/api/product-types/999');

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ update
    public function test_can_update_product_type(): void
    {
        $productType = ProductType::factory()->create(['name' => 'RAM']);

        $response = $this->putJson("/api/product-types/{$productType->id}", [
            'name' => 'DDR5 RAM',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'DDR5 RAM');

        $this->assertDatabaseHas('product_types', [
            'id' => $productType->id,
            'name' => 'DDR5 RAM',
        ]);
    }

    // ทดสอบ update validation fail
    public function test_cannot_update_product_type_without_name(): void
    {
        $productType = ProductType::factory()->create();

        $response = $this->putJson("/api/product-types/{$productType->id}", []);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ destroy
    public function test_can_delete_product_type(): void
    {
        $productType = ProductType::factory()->create(['name' => 'HDD']);

        $response = $this->deleteJson("/api/product-types/{$productType->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('product_types', [
            'id' => $productType->id,
        ]);
    }

    // ทดสอบ destroy 404
    public function test_returns_404_when_deleting_nonexistent_product_type(): void
    {
        $response = $this->deleteJson('/api/product-types/999');

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    // ทดสอบ unauthenticated
    public function test_unauthenticated_user_cannot_access_product_types(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/product-types');

        $response->assertStatus(401);
    }
}
