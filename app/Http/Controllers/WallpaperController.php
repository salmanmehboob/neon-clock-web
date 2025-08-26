<?php

namespace App\Http\Controllers;

use App\Models\Wallpaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WallpaperController extends Controller
{
    public function index()
    {
        $wallpapers = Wallpaper::latest()->paginate(10);
        return view('wallpapers.index', compact('wallpapers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $request->file('image')->store('wallpapers', 'public');

        Wallpaper::create([
            'title' => $request->title,
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Wallpaper created successfully.');
    }

    public function update(Request $request, Wallpaper $wallpaper)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = ['title' => $request->title];

        if ($request->hasFile('image')) {
            $oldPath = $wallpaper->image_path;
            $newPath = $request->file('image')->store('wallpapers', 'public');
            $data['image_path'] = $newPath;
        }

        $wallpaper->update($data);

        if (isset($oldPath) && $oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->back()->with('success', 'Wallpaper updated successfully.');
    }

    public function destroy(Wallpaper $wallpaper)
    {
        $imagePath = $wallpaper->image_path;

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        $wallpaper->delete();
        return redirect()->back()->with('success', 'Wallpaper deleted successfully.');
    }
}
