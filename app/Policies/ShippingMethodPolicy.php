<?php

namespace App\Policies;

use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShippingMethodPolicy
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
    public function view(User $user, ShippingMethod $shippingMethod): bool
    {
        // Users can view shipping methods if they're active or if they're an admin
        return $user->hasRole('admin') || $shippingMethod->is_aktif;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ShippingMethod $shippingMethod): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ShippingMethod $shippingMethod): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ShippingMethod $shippingMethod): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ShippingMethod $shippingMethod): bool
    {
        return $user->hasRole('admin');
    }
}