<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChatParticipant;
use Illuminate\Auth\Access\Response;

class ChatParticipantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view participants in conversations they're part of
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChatParticipant $chatParticipant): bool
    {
        // Users can view participants in conversations they participate in
        return $chatParticipant->conversation->participants()->where('id_user', $user->id)->exists() || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All users can be participants in conversations
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChatParticipant $chatParticipant): bool
    {
        // Only admin users, conversation admins, or the participant themselves can update
        return $user->id === $chatParticipant->id_user || 
               $chatParticipant->conversation->id_pembuat === $user->id ||
               $chatParticipant->is_admin || // If the participant is updating their own admin status
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChatParticipant $chatParticipant): bool
    {
        // Only admin or conversation creator can remove participants
        return $chatParticipant->conversation->id_pembuat === $user->id || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChatParticipant $chatParticipant): bool
    {
        return false; // Chat participants cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChatParticipant $chatParticipant): bool
    {
        return false; // Chat participants cannot be permanently deleted
    }
}