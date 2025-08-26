<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryImage extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'second_image',
        'minute_image',
        'hour_image',
        'bg_image',
        'preview_image',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->category_id) {
                $category = $model->category()->first();
                if ($category) {
                    // Count existing images for this category
                    $count = CategoryImage::where('category_id', $model->category_id)->count() + 1;

                    // Generate name like "Clock 01"
                    $model->name = $category->name . ' ' . str_pad($count, 2, '0', STR_PAD_LEFT);
                }
            }
        });
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
