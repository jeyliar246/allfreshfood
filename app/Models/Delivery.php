<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'vendor_id',
        'delivery_person_id',
        'status',
        'pickup_time',
        'delivery_time',
        'delivery_address',
        'delivery_notes',
        'tracking_number',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
        'delivery_time' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }
}
