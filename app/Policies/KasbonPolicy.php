<?php

namespace App\Policies;

use App\Models\Kasbon;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KasbonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user yang login bisa lihat daftar kasbon
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kasbon $kasbon): bool
    {
        // User bisa lihat kasbon miliknya sendiri atau admin bisa lihat semua
        return $user->id === $kasbon->user_id || $user->level === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Semua user yang login bisa ajukan kasbon
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kasbon $kasbon): bool
    {
        // Hanya admin yang bisa approve/reject kasbon
        return $user->level === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kasbon $kasbon): bool
    {
        // User bisa hapus kasbon miliknya sendiri (jika masih pending) atau admin bisa hapus semua
        if ($user->level === 'admin') {
            return true;
        }

        return $user->id === $kasbon->user_id && $kasbon->isPending();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kasbon $kasbon): bool
    {
        return $user->level === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kasbon $kasbon): bool
    {
        return $user->level === 'admin';
    }

    /**
     * Determine whether the user can approve the kasbon.
     */
    public function approve(User $user, Kasbon $kasbon): bool
    {
        return $user->level === 'admin' && $kasbon->isPending();
    }

    /**
     * Determine whether the user can reject the kasbon.
     */
    public function reject(User $user, Kasbon $kasbon): bool
    {
        return $user->level === 'admin' && $kasbon->isPending();
    }
}
