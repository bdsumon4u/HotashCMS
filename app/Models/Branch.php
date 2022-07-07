<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Branch extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'country', 'email', 'phone',
        'city', 'zip_code', 'address',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function stock($product): HasOne
    {
        $id = $product instanceof Model ? $product->getKey() : $product;
        return $this->hasOne(Stock::class)->where('product_id', $id);
    }

    public function toSearchableArray(): array
    {
        return Arr::except($this->toArray(), ['created_at', 'updated_at', 'deleted_at']);
    }
}
