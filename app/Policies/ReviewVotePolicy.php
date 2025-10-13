<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReviewVote;
use Illuminate\Auth\Access\Response;

class ReviewVotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view review votes
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReviewVote $reviewVote): bool
    {
        // All users can view a review vote
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only logged in users can vote on reviews
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReviewVote $reviewVote): bool
    {
        // Only the vote owner can update their vote
        return $user->id === $reviewVote->id_user;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReviewVote $reviewVote): bool
    {
        // Only the vote owner can delete their vote
        return $user->id === $reviewVote->id_user;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReviewVote $reviewVote): bool
    {
        return false; // Review votes cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReviewVote $reviewVote): bool
    {
        return false; // Review votes cannot be permanently deleted
    }
}