<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuisine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class, 'cuisine', 'name');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'cuisine', 'name');
    }
}
