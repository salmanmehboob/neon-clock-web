<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WallpaperResource;
use App\Models\Wallpaper;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class WallpaperController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->query('per_page', 15);

            $wallpapers = Wallpaper::latest()->paginate($perPage);

            if ($wallpapers->isEmpty()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No wallpapers found.',
                    'count'   => 0,
                    'data'    => [],
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Wallpapers fetched successfully.',
                'count'   => $wallpapers->count(),
                'total'   => $wallpapers->total(),
                'meta'    => [
                    'current_page' => $wallpapers->currentPage(),
                    'per_page'     => $wallpapers->perPage(),
                    'last_page'    => $wallpapers->lastPage(),
                    'from'         => $wallpapers->firstItem(),
                    'to'           => $wallpapers->lastItem(),
                ],
                'data' => WallpaperResource::collection($wallpapers->items()),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch wallpapers.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $wallpaper = Wallpaper::findOrFail($id);

            return response()->json([
                'status'  => true,
                'message' => 'Wallpaper fetched successfully.',
                'count'   => 1,
                'data'    => new WallpaperResource($wallpaper),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Wallpaper not found.',
                'data'    => [],
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch wallpaper.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
