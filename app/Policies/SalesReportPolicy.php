<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SalesReport;
use Illuminate\Auth\Access\Response;

class SalesReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin and seller users can view sales reports
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SalesReport $salesReport): bool
    {
        // Seller can view their own sales report, admin can view any
        return ($user->tipe_user === 'PEDAGANG' && $user->seller->id === $salesReport->id_penjual) || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin users can create sales reports
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SalesReport $salesReport): bool
    {
        // Only admin users can update sales reports
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']) && 
               $salesReport->status_laporan !== 'DISETUJUI';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SalesReport $salesReport): bool
    {
        // Only admin can delete sales reports that are not approved yet
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']) && 
               $salesReport->status_laporan !== 'DISETUJUI';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SalesReport $salesReport): bool
    {
        return false; // Sales reports cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SalesReport $salesReport): bool
    {
        return false; // Sales reports cannot be permanently deleted
    }
}