<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// class User extends Authenticatable implements MustVerifyEmail
class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLES = ['customer', 'vendor', 'admin'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'address',
        'postcode',
        'phone',
        'profile_image',
        'is_active',
        'verify_code'

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public static function rules($id = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users,email,'.$id,
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', self::ROLES),
            'address' => 'nullable|string',
            'postcode' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|max:2048',
        ];
    }
}
