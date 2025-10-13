<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MessageEdit;
use Illuminate\Auth\Access\Response;

class MessageEditPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view message edits in conversations they're part of
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MessageEdit $messageEdit): bool
    {
        // Users can view message edits in conversations they participate in
        return $messageEdit->message->conversation->participants()->where('id_user', $user->id)->exists() || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only message sender or admin can create message edits
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MessageEdit $messageEdit): bool
    {
        // Only admin can update message edits
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MessageEdit $messageEdit): bool
    {
        // Only admin can delete message edits
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MessageEdit $messageEdit): bool
    {
        return false; // Message edits cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MessageEdit $messageEdit): bool
    {
        return false; // Message edits cannot be permanently deleted
    }
}