<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryImageResource extends JsonResource
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
//            'id' => $this->id,
            // raw paths
//            'second_image'  => $this->second_image,
//            'minute_image'  => $this->minute_image,
//            'hour_image'    => $this->hour_image,
//            'bg_image'      => $this->bg_image,
            'folderName' => $this->category->slug,

            // full URLs
            'secondNeedlePath'  => $this->fullUrl($this->second_image),
            'minuteNeedlePath'  => $this->fullUrl($this->minute_image),
            'hourNeedlePath'    => $this->fullUrl($this->hour_image),
            'backgroundPath'      => $this->fullUrl($this->bg_image),
            'previewPath' => $this->fullUrl($this->preview_image),
//
//            'created_at' => optional($this->created_at)->toISOString(),
//            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
