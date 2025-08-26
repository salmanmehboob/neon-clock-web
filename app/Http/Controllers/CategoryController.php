<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('images')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'images.*'    => 'nullable|image|mimes:jpg,png,jpeg,webp|max:4096'
        ]);

        DB::transaction(function () use ($request, $validated) {
            $category = Category::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('categories', 'public');
                    $category->images()->create(['image_path' => $path]);
                }
            }
        });

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $category->load('images');
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'            => "required|string|max:255|unique:categories,name,{$category->id}",
            'description'     => 'nullable|string',
            'images.*'        => 'nullable|image|mimes:jpg,png,jpeg,webp|max:4096',
            'remove_images.*' => 'nullable|integer|exists:category_images,id',
        ]);

        DB::transaction(function () use ($request, $category, $validated) {
            $category->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            // Remove selected images
            if ($request->filled('remove_images')) {
                $toRemove = CategoryImage::whereIn('id', $request->input('remove_images', []))->get();
                foreach ($toRemove as $img) {
                    if ($img->image_path) {
                        Storage::disk('public')->delete($img->image_path);
                    }
                    $img->delete();
                }
            }

            // Add new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('categories', 'public');
                    $category->images()->create(['image_path' => $path]);
                }
            }
        });

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        DB::transaction(function () use ($category) {
            $category->load('images');
            foreach ($category->images as $img) {
                if ($img->image_path) {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }
            $category->delete();
        });

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
