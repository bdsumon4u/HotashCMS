<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Searchable;

    protected $fillable = ['name', 'slug'];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/storage/fallback-image.png')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->fit('stretch', 100, 100);
                $this->addMediaConversion('preview')
                    ->fit('stretch', 300, 300);
            });
    }

    public function toSearchableArray(): array
    {
        return Arr::except($this->toArray(), ['created_at', 'updated_at', 'deleted_at']);
    }
}
