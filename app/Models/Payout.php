<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'amount',
        'fee',
        'status',
        'payment_method',
        'reference',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2'
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
