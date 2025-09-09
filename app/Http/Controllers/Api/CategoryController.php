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

            $categories = Category::select('id', 'name', 'slug as folderName')
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
                'data' => $categories->items(), // no need for resource if simple
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
            $category = Category::select('id', 'name', 'slug')->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Category fetched successfully.',
                'count' => 1,
                'data' => $category,
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
