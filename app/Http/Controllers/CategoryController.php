<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'getPostsByCategory']);
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        return response()->json(Category::take(5)->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories', 'slug' => 'required|string|max:255|unique:categories']);
        $category = Category::create(['name' => $request->name, 'slug' => $request->slug]);
        return response()->json(['message' => 'Category created', 'category' => $category], 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(['category' => $category], 200);
    }

    public function getPostsByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return response()->json([
            'category' => $category->name,
            'posts' => $category->posts
        ]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);
        $category->update(['name' => $request->name, 'slug' => $request->slug]);
        return response()->json(['message' => 'Category updated', 'category' => $category], 200);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted'], 200);
    }
}
