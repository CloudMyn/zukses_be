<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReviewMedia;
use Illuminate\Auth\Access\Response;

class ReviewMediaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view review media
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReviewMedia $reviewMedia): bool
    {
        // All users can view review media if the associated review is active
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only users who have created a product review can add media to it
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReviewMedia $reviewMedia): bool
    {
        // Only the media owner, admin, or super admin can update
        return $user->id === $reviewMedia->review->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReviewMedia $reviewMedia): bool
    {
        // Only the media owner, admin, or super admin can delete
        return $user->id === $reviewMedia->review->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReviewMedia $reviewMedia): bool
    {
        return false; // Review media cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReviewMedia $reviewMedia): bool
    {
        return false; // Review media cannot be permanently deleted
    }
}