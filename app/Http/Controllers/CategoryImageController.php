<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryImageController extends Controller
{
    public function index()
    {
        $categoryImages = CategoryImage::with('category')->latest()->get();
        return view('category_images.index', compact('categoryImages'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('category_images.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'second_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'minute_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hour_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bg_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'preview_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = ['category_id' => $request->category_id];

            foreach (['second_image', 'minute_image', 'hour_image', 'bg_image', 'preview_image'] as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store("category_images/{$request->category_id}", 'public');
                }
            }

            CategoryImage::create($data);

            DB::commit();
            return redirect()->route('category-images.index')->with('success', 'Images uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function edit(CategoryImage $categoryImage)
    {
        $categories = Category::all();
        return view('category_images.edit', compact('categoryImage', 'categories'));
    }

    public function update(Request $request, CategoryImage $categoryImage)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'second_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'minute_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hour_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bg_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'preview_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $categoryImage->category_id = $request->category_id;

            foreach (['second_image', 'minute_image', 'hour_image', 'bg_image', 'preview_image'] as $field) {
                if ($request->hasFile($field)) {
                    // delete old image if exists
                    if ($categoryImage->$field) {
                        Storage::disk('public')->delete($categoryImage->$field);
                    }

                    $categoryImage->$field = $request->file($field)->store("category_images/{$request->category_id}", 'public');
                }
            }

            $categoryImage->save();

            DB::commit();
            return redirect()->route('category-images.index')->with('success', 'Images updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(CategoryImage $categoryImage)
    {
        DB::beginTransaction();
        try {
            if ($categoryImage->image_path && Storage::disk('public')->exists($categoryImage->image_path)) {
                Storage::disk('public')->delete($categoryImage->image_path);
            }

            $categoryImage->delete();

            DB::commit();
            return back()->with('success', 'Image deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Image deletion failed: '.$e->getMessage());
            return back()->withErrors('Failed to delete image. Please try again.');
        }
    }
}
