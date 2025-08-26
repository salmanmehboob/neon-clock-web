<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryImageResource;
use App\Models\CategoryImage;
use Illuminate\Http\Request;

class CategoryImageController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);

        $images = CategoryImage::with('category')
            ->latest()
            ->paginate($perPage);

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
    }

    public function show($id)
    {
        $image = CategoryImage::with('category')->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Category image fetched successfully.',
            'count' => 1,
            'data' => new CategoryImageResource($image),
        ]);
    }
}
