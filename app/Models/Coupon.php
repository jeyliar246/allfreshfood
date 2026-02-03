<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_coupons')
            ->withPivot('order_id', 'used_at')
            ->withTimestamps();
    }

    public function userCoupons(): HasMany
    {
        return $this->hasMany(UserCoupon::class);
    }
}
