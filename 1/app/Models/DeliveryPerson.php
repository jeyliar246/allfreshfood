<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_number',
        'license_number',
        'is_online',
        'current_lat',
        'current_lng'
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'current_lat' => 'decimal:8',
        'current_lng' => 'decimal:8'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderDeliveries(): HasMany
    {
        return $this->hasMany(OrderDelivery::class);
    }
}
