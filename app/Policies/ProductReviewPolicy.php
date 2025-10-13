<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductReview;
use Illuminate\Auth\Access\Response;

class ProductReviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view product reviews
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductReview $productReview): bool
    {
        // All users can view a product review if it's active
        return $productReview->status_ulasan === 'AKTIF' || 
               $user->id === $productReview->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only users who have purchased the product can create reviews
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductReview $productReview): bool
    {
        // Only the review owner, admin, or super admin can update
        return $user->id === $productReview->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductReview $productReview): bool
    {
        // Only the review owner, admin, or super admin can delete
        return $user->id === $productReview->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductReview $productReview): bool
    {
        return false; // Product reviews cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductReview $productReview): bool
    {
        return false; // Product reviews cannot be permanently deleted
    }
}