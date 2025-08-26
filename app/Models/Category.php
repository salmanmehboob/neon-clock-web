<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'status'];

    /**
     * Auto-generate slug when creating/updating.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(static function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
    
    public function images(): HasMany
    {
        return $this->hasMany(CategoryImage::class);
    }
}
