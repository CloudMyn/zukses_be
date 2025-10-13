<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SearchHistory;
use Illuminate\Auth\Access\Response;

class SearchHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view their own search history
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SearchHistory $searchHistory): bool
    {
        // Users can only view their own search history or if they're admin
        return $user->id === $searchHistory->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All users can create search history entries (including guests)
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SearchHistory $searchHistory): bool
    {
        // Only admin users can update search history
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SearchHistory $searchHistory): bool
    {
        // Users can delete their own search history, admin can delete any
        return $user->id === $searchHistory->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SearchHistory $searchHistory): bool
    {
        return false; // Search history cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SearchHistory $searchHistory): bool
    {
        return false; // Search history cannot be permanently deleted
    }
}