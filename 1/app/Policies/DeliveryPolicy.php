<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Delivery;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'delivery';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Delivery $delivery): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'delivery') {
            return $delivery->delivery_person_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Delivery $delivery): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'delivery') {
            return $delivery->delivery_person_id === $user->id && 
                   in_array($delivery->status, ['pending', 'picked_up']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Delivery $delivery): bool
    {
        return $user->role === 'admin' && $delivery->status === 'pending';
    }

    /**
     * Determine whether the user can mark the delivery as picked up.
     */
    public function markAsPickedUp(User $user, Delivery $delivery): bool
    {
        if ($user->role === 'admin') {
            return $delivery->status === 'pending';
        }

        if ($user->role === 'delivery') {
            return $delivery->delivery_person_id === $user->id && 
                   $delivery->status === 'pending';
        }

        return false;
    }

    /**
     * Determine whether the user can mark the delivery as delivered.
     */
    public function markAsDelivered(User $user, Delivery $delivery): bool
    {
        if ($user->role === 'admin') {
            return $delivery->status === 'picked_up';
        }

        if ($user->role === 'delivery') {
            return $delivery->delivery_person_id === $user->id && 
                   $delivery->status === 'picked_up';
        }

        return false;
    }
}
