<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'vendor';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vendor $vendor): bool
    {
        return $user->role === 'admin' || 
               ($user->role === 'vendor' && $user->id === $vendor->user_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'vendor';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vendor $vendor): bool
    {
        return $user->role === 'admin' || 
               ($user->role === 'vendor' && $user->id === $vendor->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vendor $vendor): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can approve the vendor.
     */
    public function approve(User $user): bool
    {
        return $user->role === 'admin';
    }
}
