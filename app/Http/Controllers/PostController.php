<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['getRelatedPosts', 'index', 'show', 'getPost', 'getAuthorPosts']);
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy', 'publish', 'draft']);
    }


    public function index()
    {
        return PostResource::collection(Post::where('status', 'published')->latest()->get());
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'slug' => 'required|string|max:255|unique:posts',
            'status' => 'required|in:draft,published',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validated['status'] = $validated['status'] ?? 'draft';

        $post = $request->user()->posts()->create($validated);
        return new PostResource($post);
    }

    public function getPost($slug)
    {
        $post = Post::with(['author:name,id', 'category:name,id'])
        ->where('slug', $slug)->firstOrFail();

        return response()->json([
            'slug' => $post->slug,
            'title' => $post->title,
            'content' => $post->content,
            'image' => $post->image,
            'excerpt' =>$post->excerpt,
            'category' => [
                'name' => $post->category->name,
                'id' => $post->category->id
            ],
            'author' => [
                'name' => $post->author->name,
                'id' => $post->author->id
            ],
            'created_at' => $post->created_at,

            ]);
    }

    public function getRelatedPosts($slug)
    {
        $post = Post::where('slug', $slug)->firstorFail();
        $relatedPosts = Post::where('category_id', $post->category_id)
        ->where('slug', '!=', $slug)
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get(['slug', 'title', 'excerpt', 'image', 'created_at']);

        return response()->json($relatedPosts);
    }

    public function getAuthorPosts($name)
    {
        $author = User::where('name', $name)->firstorFail();

        $posts = Post::where('user_id'. $author->id)
        ->orderBy('created_at', 'desc')->get(['slug', 'title', 'excerpt', 'image', 'created_at', 'read_time']);

        return response()->json($posts);
    }


    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'slug' => 'sometimes|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $post->update($validated);
        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted']);
    }

    public function publish(Post $post)
    {
        $post->update(['status' => 'published']);
        return response()->json(['message' => 'Post published']);
    }

    public function draft(Post $post)
    {
        $post->update(['status' => 'draft']);
        return response()->json(['message' => 'Post set to draft']);
    }
}

