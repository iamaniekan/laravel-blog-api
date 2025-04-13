<?php

namespace Tests\Unit;

use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_post_collection()
    {
        Post::factory()->count(3)->create();

        $controller = new PostController();
        $response = $controller->index();

        $this->assertCount(3, $response);
    }

    public function test_show_returns_post_by_slug()
    {
        $post = Post::factory()->create();

        $controller = new PostController();
        $response = $controller->show($post);

        $this->assertEquals($post->slug, $response->slug);
    }

    public function test_store_creates_new_post()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'title' => 'New Post',
            'content' => 'Post content',
            'slug' => 'new-post',
            'status' => 'draft',
            'category_id' => $category->id,
        ];
        $response = $this->actingAs($user)->postJson('/api/posts', $data);  
        $response->assertCreated();

        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
    }
    public function test_update_modifies_existing_post()
    {
    
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $this->actingAs($user);

        $data = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'category_id' => $category->id,
        ];

        $controller = new PostController();
        $response = $controller->update(new Request($data), $post);

        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    }

    public function test_destroy_deletes_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $controller = new PostController();
        $response = $controller->destroy($post);

        $this->assertDatabaseMissing($post);
    }
}
