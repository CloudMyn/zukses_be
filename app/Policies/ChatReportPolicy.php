<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChatReport;
use Illuminate\Auth\Access\Response;

class ChatReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin users can view chat reports
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChatReport $chatReport): bool
    {
        // Only admin users can view chat reports
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All users can create reports for inappropriate content
        return in_array($user->tipe_user, ['PELANGGAN', 'ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChatReport $chatReport): bool
    {
        // Only admin users can update reports
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChatReport $chatReport): bool
    {
        // Only super admin can delete reports
        return $user->tipe_user === 'SUPER_ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChatReport $chatReport): bool
    {
        return false; // Chat reports cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChatReport $chatReport): bool
    {
        return false; // Chat reports cannot be permanently deleted
    }
}