<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name', 'slug', 'sku', 'barcode', 'brand_id',
        'regular_price', 'sale_price', 'schedule',
        'sale_start_date', 'sale_end_date',
        'net_tax', 'tax_method', 'note',
        'attributes', 'has_variation',
        'is_active',
    ];

    protected $casts = [
        'attributes' => AsArrayObject::class,
    ];

    public function searchableAs(): string
    {
        return config('scout.prefix').tenant('id').'_'.$this->getTable();
    }

    public function toSearchableArray(): array
    {
        return Arr::except($this->toArray(), ['created_at', 'updated_at', 'deleted_at']);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(Variation::class);
    }
}
