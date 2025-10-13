<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChatConversation;
use Illuminate\Auth\Access\Response;

class ChatConversationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view their own conversations
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChatConversation $chatConversation): bool
    {
        // Users can view conversations they participate in
        return $chatConversation->participants()->where('id_user', $user->id)->exists() || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All users can create conversations
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChatConversation $chatConversation): bool
    {
        // Only admin or conversation creator can update
        return $user->id === $chatConversation->id_pembuat || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChatConversation $chatConversation): bool
    {
        // Only admin or conversation creator can delete
        return $user->id === $chatConversation->id_pembuat || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChatConversation $chatConversation): bool
    {
        return false; // Chat conversations cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChatConversation $chatConversation): bool
    {
        return false; // Chat conversations cannot be permanently deleted
    }
}