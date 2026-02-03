<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCommissionRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'commission_rate',
        'rule_type',
        'conditions',
        'is_active'
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'conditions' => 'array',
        'is_active' => 'boolean'
    ];
}
