<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute as AttributeCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Attribute extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['group', 'name', 'slug', 'values'];

    public function values(): AttributeCast
    {
        return new AttributeCast(
            get: fn ($value) => json_decode($value, true) ?? [],
            set: fn ($value) => Str::of($value)->explode('|')
                ->transform(fn ($item) => trim($item))
                ->sort()->values()->toJson(),
        );
    }

    public function toSearchableArray(): array
    {
        return Arr::except($this->toArray(), ['created_at', 'updated_at', 'deleted_at']);
    }
}
