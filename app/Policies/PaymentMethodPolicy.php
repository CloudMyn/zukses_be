<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Auth\Access\Response;

class PaymentMethodPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin users can view payment methods
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentMethod $paymentMethod): bool
    {
        // Only admin users can view a payment method
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin can create payment methods
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentMethod $paymentMethod): bool
    {
        // Only super admin can update payment methods
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentMethod $paymentMethod): bool
    {
        // Only super admin can delete payment methods
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentMethod $paymentMethod): bool
    {
        return false; // Payment methods cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentMethod $paymentMethod): bool
    {
        return false; // Payment methods cannot be permanently deleted
    }
}