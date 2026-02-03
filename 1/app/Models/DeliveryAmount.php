<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAmount extends Model
{
    protected $table = 'delivery_amounts';

    protected $fillable = [
        'amount',
        'outside',
        'multi_store',
        'created_by',
        'updated_by',
    ];
}
