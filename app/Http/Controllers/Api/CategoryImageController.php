<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryImageResource;
use App\Models\CategoryImage;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CategoryImageController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->query('per_page', 15);

            $images = CategoryImage::with('category')
                ->latest()
                ->paginate($perPage);

            if ($images->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No category images found.',
                    'count' => 0,
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Category images fetched successfully.',
                'count' => $images->count(),
                'total' => $images->total(),
                'meta' => [
                    'current_page' => $images->currentPage(),
                    'per_page' => $images->perPage(),
                    'last_page' => $images->lastPage(),
                    'from' => $images->firstItem(),
                    'to' => $images->lastItem(),
                ],
                'data' => CategoryImageResource::collection($images->items()),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch category images.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $image = CategoryImage::with('category')->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Category image fetched successfully.',
                'count' => 1,
                'data' => new CategoryImageResource($image),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Category image not found.',
                'data' => [],
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch category image.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getImagesByCategory($slug)
    {
        try {
            $images = CategoryImage::whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
                ->with('category:id,name,slug')
                ->latest()
                ->get();

            if ($images->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No images found for this category.',
                    'count' => 0,
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Images fetched successfully for category: ' . $slug,
                'count' => $images->count(),
                'data' => CategoryImageResource::collection($images),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch images.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
