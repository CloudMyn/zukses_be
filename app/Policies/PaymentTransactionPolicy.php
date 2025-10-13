<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PaymentTransaction;
use Illuminate\Auth\Access\Response;

class PaymentTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin and seller users can view payment transactions
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentTransaction $paymentTransaction): bool
    {
        // Users can view their own payment transactions
        // Admins can view any payment transaction
        return $user->id === $paymentTransaction->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All users can create payment transactions for their orders
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentTransaction $paymentTransaction): bool
    {
        // Only admin users can update payment transactions
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentTransaction $paymentTransaction): bool
    {
        // Only super admin can delete payment transactions
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentTransaction $paymentTransaction): bool
    {
        return false; // Payment transactions cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentTransaction $paymentTransaction): bool
    {
        return false; // Payment transactions cannot be permanently deleted
    }
}