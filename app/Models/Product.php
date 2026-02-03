<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vendor_id',
        'category_id',
        'category',
        'price',
        'pprice',
        'original_price',
        'description',
        'image',
        'stock',
        'status',
        'cuisine',
        'halal', 
        'vegan',
        'gluten_free',
        'organic',
        'fair_trade',
        'non_GMO',
        'deal',
        'discount',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cuisine(): BelongsTo
    {
        return $this->belongsTo(Cuisine::class);
    }
}
