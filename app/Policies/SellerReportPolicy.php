<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SellerReport;
use Illuminate\Auth\Access\Response;

class SellerReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin and seller users can view reports
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SellerReport $sellerReport): bool
    {
        // Seller can view their own report, admin can view any
        return ($user->tipe_user === 'PEDAGANG' && $user->seller->id === $sellerReport->id_penjual) || 
               in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin users can create reports for sellers
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SellerReport $sellerReport): bool
    {
        // Only admin users can update reports
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']) && 
               $sellerReport->status_laporan !== 'DISETUJUI';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SellerReport $sellerReport): bool
    {
        // Only admin can delete reports that are not approved yet
        return in_array($user->tipe_user, ['ADMIN', 'SUPER_ADMIN']) && 
               $sellerReport->status_laporan !== 'DISETUJUI';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SellerReport $sellerReport): bool
    {
        return false; // Seller reports cannot be restored
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SellerReport $sellerReport): bool
    {
        return false; // Seller reports cannot be permanently deleted
    }
}