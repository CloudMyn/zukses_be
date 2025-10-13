<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Auth\Access\Response;

class UserActivityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin users can view user activities
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserActivity $userActivity): bool
    {
        // Users can view their own activities, admin can view any
        return $user->id === $userActivity->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // System processes create user activities, but users can create them too
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserActivity $userActivity): bool
    {
        // Only admin users can update user activities
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserActivity $userActivity): bool
    {
        // Only admin users can delete user activities
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserActivity $userActivity): bool
    {
        return false; // User activities cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserActivity $userActivity): bool
    {
        return false; // User activities cannot be permanently deleted
    }
}