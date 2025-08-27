<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Wallpaper;
use App\Models\CategoryImage;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // ... existing code ...
    }

    public function index()
    {
        $stats = [
            'categories'       => Category::count(),
            'wallpapers'       => Wallpaper::count(),
            'category_images'  => CategoryImage::count(),
            'users'            => User::count(),
        ];

        $recentWallpapers = Wallpaper
           ::latest()
            ->limit(5)
            ->get();

        return view('home', compact('stats', 'recentWallpapers'));
    }
}
