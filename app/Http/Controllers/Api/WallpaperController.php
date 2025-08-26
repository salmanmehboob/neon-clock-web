<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WallpaperResource;
use App\Models\Wallpaper;
use Illuminate\Http\Request;

class WallpaperController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);

        $wallpapers = Wallpaper::latest()->paginate($perPage);

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
    }

    public function show($id)
    {
        $wallpaper = Wallpaper::findOrFail($id);

        return response()->json([
            'status'  => true,
            'message' => 'Wallpaper fetched successfully.',
            'count'   => 1,
            'data'    => new WallpaperResource($wallpaper),
        ]);
    }
}
