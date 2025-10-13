<?php

namespace App\Policies;

use App\Models\SellerShippingMethod;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerShippingMethodPolicy
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
    public function view(User $user, SellerShippingMethod $sellerShippingMethod): bool
    {
        // Users can view seller shipping methods if they're the owner or an admin
        return $user->hasRole('admin') || $sellerShippingMethod->id_seller === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only sellers and admins can create seller shipping methods
        return $user->hasRole('admin') || $user->tipe_user === 'PEDAGANG';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SellerShippingMethod $sellerShippingMethod): bool
    {
        // Only the owner seller or admin can update
        return $user->hasRole('admin') || $sellerShippingMethod->id_seller === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SellerShippingMethod $sellerShippingMethod): bool
    {
        // Only the owner seller or admin can delete
        return $user->hasRole('admin') || $sellerShippingMethod->id_seller === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SellerShippingMethod $sellerShippingMethod): bool
    {
        // Only the owner seller or admin can restore
        return $user->hasRole('admin') || $sellerShippingMethod->id_seller === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SellerShippingMethod $sellerShippingMethod): bool
    {
        // Only admins can permanently delete
        return $user->hasRole('admin');
    }
}