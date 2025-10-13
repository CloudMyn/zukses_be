<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MessageAttachment;
use Illuminate\Auth\Access\Response;

class MessageAttachmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users can view attachments in conversations they're part of
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MessageAttachment $messageAttachment): bool
    {
        // Users can view attachments in conversations they participate in
        return $messageAttachment->message->conversation->participants()->where('id_user', $user->id)->exists() || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All users can create attachments when sending messages
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MessageAttachment $messageAttachment): bool
    {
        // Only admin can update attachments info
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MessageAttachment $messageAttachment): bool
    {
        // Only message sender, admin or conversation creator can delete attachments
        return $user->id === $messageAttachment->message->id_pengirim || 
               $messageAttachment->message->conversation->id_pembuat === $user->id ||
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MessageAttachment $messageAttachment): bool
    {
        return false; // Message attachments cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MessageAttachment $messageAttachment): bool
    {
        return false; // Message attachments cannot be permanently deleted
    }
}