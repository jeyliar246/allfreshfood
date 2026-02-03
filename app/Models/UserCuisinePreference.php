<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCuisinePreference extends Model
{
    use HasFactory;

    protected $table = 'user_cuisine_preferences';

    protected $fillable = [
        'user_id',
        'cuisine_id',
        'preference_level'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cuisine(): BelongsTo
    {
        return $this->belongsTo(Cuisine::class);
    }
}
