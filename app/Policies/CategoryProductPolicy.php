<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CategoryProduct;
use Illuminate\Auth\Access\Response;

class CategoryProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view categories
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CategoryProduct $category): bool
    {
        // All authenticated users can view a specific category
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only ADMIN and PEDAGANG can create categories
        return in_array($user->tipe_user, ['ADMIN', 'PEDAGANG']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CategoryProduct $category): bool
    {
        // ADMIN can update any category
        if ($user->tipe_user === 'ADMIN') {
            return true;
        }

        // PEDAGANG cannot update system categories (level 0)
        if ($user->tipe_user === 'PEDAGANG' && $category->level_kategori === 0) {
            return false;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CategoryProduct $category): bool
    {
        // Only ADMIN can delete categories
        if ($user->tipe_user !== 'ADMIN') {
            return false;
        }

        // Check if category can be safely deleted
        return $category->canBeDeleted();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CategoryProduct $category): bool
    {
        // Only ADMIN can restore deleted categories
        return $user->tipe_user === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CategoryProduct $category): bool
    {
        // Only ADMIN can permanently delete categories
        if ($user->tipe_user !== 'ADMIN') {
            return false;
        }

        // Check if category can be safely deleted
        return $category->canBeDeleted();
    }

    /**
     * Determine whether the user can toggle featured status.
     */
    public function toggleFeatured(User $user, CategoryProduct $category): bool
    {
        // Only ADMIN can toggle featured status
        return $user->tipe_user === 'ADMIN';
    }

    /**
     * Determine whether the user can toggle active status.
     */
    public function toggleActive(User $user, CategoryProduct $category): bool
    {
        // ADMIN can toggle any category
        if ($user->tipe_user === 'ADMIN') {
            return true;
        }

        // PEDAGANG cannot deactivate system categories (level 0)
        if ($user->tipe_user === 'PEDAGANG' && $category->level_kategori === 0) {
            return false;
        }

        return false;
    }

    /**
     * Determine whether the user can reorder categories.
     */
    public function reorder(User $user): bool
    {
        // Only ADMIN can reorder categories
        return $user->tipe_user === 'ADMIN';
    }

    /**
     * Determine whether the user can manage category hierarchy.
     */
    public function manageHierarchy(User $user): bool
    {
        // Only ADMIN can manage category hierarchy
        return $user->tipe_user === 'ADMIN';
    }

    /**
     * Determine whether the user can view category statistics.
     */
    public function viewStatistics(User $user, CategoryProduct $category): bool
    {
        // ADMIN can view all statistics
        if ($user->tipe_user === 'ADMIN') {
            return true;
        }

        // PEDAGANG can view statistics for their own products
        if ($user->tipe_user === 'PEDAGANG') {
            // Check if seller has products in this category
            // This would need to be implemented based on the seller relationship
            return true;
        }

        return false;
    }
}