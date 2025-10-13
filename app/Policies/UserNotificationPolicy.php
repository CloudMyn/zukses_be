<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Auth\Access\Response;

class UserNotificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view their own notifications
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserNotification $userNotification): bool
    {
        // Users can only view their own notifications
        return $user->id === $userNotification->id_user;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only system processes or admin users can create notifications
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserNotification $userNotification): bool
    {
        // Only the notification owner or admin can update
        return $user->id === $userNotification->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserNotification $userNotification): bool
    {
        // Only the notification owner can delete their notification
        return $user->id === $userNotification->id_user;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserNotification $userNotification): bool
    {
        return false; // User notifications cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserNotification $userNotification): bool
    {
        return false; // User notifications cannot be permanently deleted
    }
}