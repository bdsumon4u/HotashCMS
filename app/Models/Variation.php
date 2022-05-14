<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Variation extends Model implements HasMedia
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
        'type', 'name', 'sku', 'barcode', 'regular_price', 'discount_amount', 'discount_type',
        'sale_price', 'enabled', 'schedule', 'sale_start_date', 'sale_end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
        'schedule' => 'boolean',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
    ];
}
