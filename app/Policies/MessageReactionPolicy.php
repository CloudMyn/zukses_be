<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MessageReaction;
use Illuminate\Auth\Access\Response;

class MessageReactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view reactions in conversations they're part of
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MessageReaction $messageReaction): bool
    {
        // Users can view reactions in conversations they participate in
        return $messageReaction->message->conversation->participants()->where('id_user', $user->id)->exists() || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All users can react to messages in conversations they're part of
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MessageReaction $messageReaction): bool
    {
        // Only the user who reacted or admin can update
        return $user->id === $messageReaction->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MessageReaction $messageReaction): bool
    {
        // Only the user who reacted or admin can delete
        return $user->id === $messageReaction->id_user || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MessageReaction $messageReaction): bool
    {
        return false; // Message reactions cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MessageReaction $messageReaction): bool
    {
        return false; // Message reactions cannot be permanently deleted
    }
}