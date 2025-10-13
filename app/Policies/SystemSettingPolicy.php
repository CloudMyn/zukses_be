<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Auth\Access\Response;

class SystemSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin users can view system settings
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SystemSetting $systemSetting): bool
    {
        // Only admin users can view system settings
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin can create system settings
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SystemSetting $systemSetting): bool
    {
        // Only super admin can update system settings
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SystemSetting $systemSetting): bool
    {
        // Only super admin can delete system settings
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SystemSetting $systemSetting): bool
    {
        return false; // System settings cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SystemSetting $systemSetting): bool
    {
        return false; // System settings cannot be permanently deleted
    }
}