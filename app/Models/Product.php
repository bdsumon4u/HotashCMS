<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type', 'name', 'slug', 'sku', 'barcode',
        'regular_price', 'discount_amount', 'discount_type', 'sale_price',
        'enabled', 'scheduled', 'sale_start_date', 'sale_end_date',
        'description', 'attributes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
        'scheduled' => 'boolean',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'attributes' => AsArrayObject::class,
    ];

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class);
    }

    public function purchases(): BelongsToMany
    {
        return $this->belongsToMany(Purchase::class)->using(Stock::class);
    }

//    public function stocks(): MorphMany
//    {
//        return $this->morphMany(Stock::class, 'saleable');
//    }
//
//    public function stock($branch): MorphOne
//    {
//        $id = $branch instanceof Model ? $branch->getKey() : $branch;
//        return $this->morphOne(Stock::class, 'saleable')->where('branch_id', $id);
//    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function toSearchableArray(): array
    {
        return $this->only([
            'type', 'name', 'slug', 'sku', 'barcode',
            'description', 'attributes',
        ]);
    }
}
