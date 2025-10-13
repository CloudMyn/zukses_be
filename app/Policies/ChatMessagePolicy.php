<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Auth\Access\Response;

class ChatMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view messages in conversations they're part of
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChatMessage $chatMessage): bool
    {
        // Users can view messages in conversations they participate in
        return $chatMessage->conversation->participants()->where('id_user', $user->id)->exists() || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All users can send messages in conversations they're part of
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChatMessage $chatMessage): bool
    {
        // Only message sender or admin can update (typically only for editing message content)
        return $user->id === $chatMessage->id_pengirim || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChatMessage $chatMessage): bool
    {
        // Only message sender or admin can delete messages
        return $user->id === $chatMessage->id_pengirim || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChatMessage $chatMessage): bool
    {
        return false; // Chat messages cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChatMessage $chatMessage): bool
    {
        return false; // Chat messages cannot be permanently deleted
    }
}