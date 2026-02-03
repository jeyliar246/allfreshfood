<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'delivery_fee',
        'min_order_amount',
        'polygon_coordinates',
        'is_active'
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'polygon_coordinates' => 'array',
        'is_active' => 'boolean'
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
