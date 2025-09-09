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

        static::saving(static function ($category) {
            // Always regenerate slug from name
            $category->slug = lcfirst(str_replace(' ', '', ucwords($category->name)));
        });
        
//        static::creating(static function ($category) {
//            if (empty($category->slug)) {
//                $slug = lcfirst(str_replace(' ', '', ucwords($category->name)));
//                $category->slug = $slug;
//            }
//        });
//
//        static::updating(static function ($category) {
//            if ($category->isDirty('name')) {
//                $slug = lcfirst(str_replace(' ', '', ucwords($category->name)));
//                $category->slug = $slug;
//            }
//        });
    }


    public function images(): HasMany
    {
        return $this->hasMany(CategoryImage::class);
    }
}
