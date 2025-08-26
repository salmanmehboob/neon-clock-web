<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);

        $categories = Category::with('images')
            ->withCount('images')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully.',
            'count' => $categories->count(),
            'total' => $categories->total(),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ],
            'data' => CategoryResource::collection($categories->items()),
        ]);
    }

    public function show($id)
    {
        $category = Category::with('images')
            ->withCount('images')
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Category fetched successfully.',
            'count' => 1,
            'data' => new CategoryResource($category),
        ]);
    }
}
