<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAnalytics extends Model
{
    use HasFactory;

    protected $table = 'product_analytics';

    protected $fillable = [
        'product_id',
        'date',
        'views',
        'clicks',
        'orders_count'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
