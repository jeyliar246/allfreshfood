<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_amount',
        'commission_rate',
        'commission_amount',
        'is_paid',
        'paid_at'
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
