<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->query('per_page', 15);

            $categories = Category::with('images')
                ->withCount('images')
                ->latest()
                ->paginate($perPage);

            if ($categories->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No categories found.',
                    'count' => 0,
                    'data' => [],
                ], 404);
            }

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
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch categories.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::with('images')
                ->withCount('images')
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Category fetched successfully.',
                'count' => 1,
                'data' => new CategoryResource($category),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found.',
                'data' => [],
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
