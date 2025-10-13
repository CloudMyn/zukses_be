<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AdminUser;
use Illuminate\Auth\Access\Response;

class AdminUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only super admin can view all admin users
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AdminUser $adminUser): bool
    {
        // Only super admin and same-level admin can view
        return $user->tipe_user === 'SUPER_ADMIN' || 
               ($user->tipe_user === 'ADMIN' && $user->id === $adminUser->id_user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin can create admin users
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AdminUser $adminUser): bool
    {
        // Only super admin can update admin users
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AdminUser $adminUser): bool
    {
        // Only super admin can delete other admin users
        return $user->tipe_user === 'SUPER_ADMIN' && $user->id !== $adminUser->id_user;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AdminUser $adminUser): bool
    {
        return false; // Admin users cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AdminUser $adminUser): bool
    {
        return false; // Admin users cannot be permanently deleted
    }
}