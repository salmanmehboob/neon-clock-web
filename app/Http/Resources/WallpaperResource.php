<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class WallpaperResource extends JsonResource
{
    protected function fullUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        return url(Storage::url($path));
    }

    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'image_path' => $this->image_path,
            'image_url'  => $this->fullUrl($this->image_path),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
