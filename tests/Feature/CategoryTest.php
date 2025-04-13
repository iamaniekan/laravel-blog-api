<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_get_posts_by_category()
    {
        $category = Category::factory()->create();
        $response = $this->getJson("/api/category/{$category->slug}/posts");

        $response->assertOk();
    }

    public function test_authenticated_user_can_create_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/categories', [
            'name' => 'Technology',
            'slug' => 'technology'
        ]);

        $response->assertCreated();
    }

    public function test_authenticated_user_can_update_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user);

        $response = $this->putJson("/api/categories/{$category->slug}", [
            'name' => 'Updated Category',
            'slug' => 'updated-category'
        ]);

        $response->assertOk();
    }

    public function test_authenticated_user_can_delete_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user);

        $response = $this->deleteJson("/api/categories/{$category->slug}");

        $response->assertOk();
    }
}
