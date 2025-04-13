<?php

namespace Tests\Unit;

use App\Http\Controllers\PostController;
use App\Models\Post;
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
}
