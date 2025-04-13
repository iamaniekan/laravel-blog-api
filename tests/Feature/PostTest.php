<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_all_posts()
    {
        Post::factory(5)->create();

        $response = $this->getJson('/api/posts');

        $response->assertOk();
    }

    public function test_guest_can_view_post_by_slug()
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->slug}");

        $response->assertOk();
    }

    public function test_guest_can_get_raw_post()
    {
        $post = Post::factory()->create();
        
        $response = $this->getJson("/api/posts/get/{$post->slug}");

        $response->assertOk();
    }

    public function test_guest_can_fetch_related_posts()
    {
        $post = Post::factory()->create();
        Post::factory(3)->create(['category_id' => $post->category_id]);

        $response = $this->getJson("/api/posts/related/{$post->slug}");

        $response->assertOk();
    }

    public function test_authenticated_user_can_create_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/posts', [
            'title' => 'Benefit of eating good food',
            'content' => 'You will surely enjoy it.',
            'slug' => 'benefit-of-eating-good-food',
            'category_id' => $post->category_id,
            'status' => 'published',
            

        ]);

        $response->assertCreated();
    }

    public function test_authenticated_user_can_update_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated Title'
        ]);

        $response->assertOk();
    }

    public function test_authenticated_user_can_delete_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $response = $this->deleteJson("/api/posts/{$post->slug}");

        $response->assertOk();
    }
}
