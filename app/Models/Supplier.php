<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Supplier extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'email', 'phone'];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function toSearchableArray(): array
    {
        return Arr::except($this->toArray(), ['created_at', 'updated_at', 'deleted_at']);
    }
}
