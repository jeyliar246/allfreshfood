<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformAnalytics extends Model
{
    use HasFactory;

    protected $table = 'platform_analytics';

    protected $fillable = [
        'date',
        'new_users',
        'new_vendors',
        'orders_count',
        'orders_total',
        'commission_total'
    ];

    protected $casts = [
        'date' => 'date',
        'orders_total' => 'decimal:2',
        'commission_total' => 'decimal:2'
    ];
}
