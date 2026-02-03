<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'order_number',
        'status',
        'total',
        'ptotal',
        'delivery_address',
        'delivery_fee',
        'payment_method',
        'payment_status',
        'phone',
        'fulfillment_method',
        'guest_name',
        'guest_email',
        'guest_phone',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'ptotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
    ];

    /**
     * Get the user that placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vendor that the order belongs to.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    // Delivery relation can be added/adjusted when Delivery model/table exists

    /**
     * Scope a query to only include orders for a specific vendor.
     */
    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Scope a query to only include orders with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if the order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Calculate the total amount for the order.
     */
    public function calculateTotal(): void
    {
        $this->total_amount = $this->subtotal + $this->tax + $this->shipping_fee;
    }
}
