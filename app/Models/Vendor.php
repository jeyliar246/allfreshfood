<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Vendor extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'cuisine',
        'description',
        'location',
        'postcode',
        'phone',
        'email',
        'image',
        'cover_image',
        'verified',
        'featured',
        'min_order',
        'free_delivery_over',
        'opening_hours',
        'delivery_time',
        'delivery_fee',
        'account_name',
        'account_number',
        'sort_code',
        'is_approved',
        'approved_at',
    ];
    
    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'verified' => 'boolean',
        'featured' => 'boolean',
        'opening_hours' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
