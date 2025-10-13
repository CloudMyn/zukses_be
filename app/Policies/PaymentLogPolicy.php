<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PaymentLog;
use Illuminate\Auth\Access\Response;

class PaymentLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin users can view payment logs
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentLog $paymentLog): bool
    {
        // Only admin users can view payment logs
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only system processes can create payment logs (not regular users)
        return false; // In real application, this might be true for payment processing services
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentLog $paymentLog): bool
    {
        // Payment logs should not be updated after creation
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentLog $paymentLog): bool
    {
        // Only super admin can delete payment logs
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentLog $paymentLog): bool
    {
        return false; // Payment logs cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentLog $paymentLog): bool
    {
        return false; // Payment logs cannot be permanently deleted
    }
}