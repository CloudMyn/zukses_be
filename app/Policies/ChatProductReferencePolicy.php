<?php

namespace App\Policies;

use App\Models\ChatProductReference;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatProductReferencePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChatProductReference $chatProductReference): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->tipe_user === 'ADMIN' || $user->tipe_user === 'PELANGGAN' || $user->tipe_user === 'PEDAGANG';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChatProductReference $chatProductReference): bool
    {
        return $user->tipe_user === 'ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChatProductReference $chatProductReference): bool
    {
        return $user->tipe_user === 'ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChatProductReference $chatProductReference): bool
    {
        return $user->tipe_user === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChatProductReference $chatProductReference): bool
    {
        return $user->tipe_user === 'ADMIN';
    }
}