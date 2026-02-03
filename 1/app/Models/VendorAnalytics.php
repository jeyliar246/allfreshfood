<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorAnalytics extends Model
{
    use HasFactory;

    protected $table = 'vendor_analytics';

    protected $fillable = [
        'vendor_id',
        'date',
        'views',
        'clicks',
        'orders_count',
        'orders_total'
    ];

    protected $casts = [
        'date' => 'date',
        'orders_total' => 'decimal:2'
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
