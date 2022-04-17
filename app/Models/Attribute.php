<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute as AttributeCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Attribute extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['group', 'name', 'values'];

    public function values(): AttributeCast
    {
        return new AttributeCast(
            get: fn ($value) => json_decode($value, true) ?? [],
            set: fn ($value) => Str::of($value)->explode('|')
                ->transform(fn ($item) => trim($item))
                ->sort()->values()->toJson(),
        );
    }

    public function searchableAs(): string
    {
        return config('scout.prefix').tenant('id').'_'.$this->getTable();
    }

    public function toSearchableArray(): array
    {
        return Arr::except($this->toArray(), ['created_at', 'updated_at', 'deleted_at']);
    }
}
