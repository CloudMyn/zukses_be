<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MessageStatus;
use Illuminate\Auth\Access\Response;

class MessageStatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only users can view statuses for messages in conversations they're part of
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MessageStatus $messageStatus): bool
    {
        // Users can view statuses for messages in conversations they participate in
        return $messageStatus->message->conversation->participants()->where('id_user', $user->id)->exists() || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All users can create status updates for messages they receive
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MessageStatus $messageStatus): bool
    {
        // Only the user the status is for, or admin can update
        return $user->id === $messageStatus->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MessageStatus $messageStatus): bool
    {
        // Only admin can delete message statuses
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MessageStatus $messageStatus): bool
    {
        return false; // Message statuses cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MessageStatus $messageStatus): bool
    {
        return false; // Message statuses cannot be permanently deleted
    }
}