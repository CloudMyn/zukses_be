<?php

namespace App\Policies;

use App\Models\OrderShipping;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderShippingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrderShipping $orderShipping): bool
    {
        // Users can view order shipping if they're the customer, seller, or admin
        return $user->hasRole('admin') || 
               $orderShipping->order->id_customer === $user->id ||
               $orderShipping->order->id_seller === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create order shipping records
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrderShipping $orderShipping): bool
    {
        // Only admins can update order shipping records
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderShipping $orderShipping): bool
    {
        // Only admins can delete order shipping records
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrderShipping $orderShipping): bool
    {
        // Only admins can restore order shipping records
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrderShipping $orderShipping): bool
    {
        // Only admins can permanently delete order shipping records
        return $user->hasRole('admin');
    }
}