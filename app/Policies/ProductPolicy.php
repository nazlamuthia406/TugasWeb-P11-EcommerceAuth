<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

/**
 * Otorisasi produk (setara "PostPolicy" pada slide, resource utamanya di sini adalah Product).
 *
 *  admin  -> boleh semuanya (lewat before())
 *  editor -> boleh EDIT semua produk, tapi hanya boleh HAPUS produk miliknya
 *  user   -> hanya boleh edit & hapus produk miliknya sendiri
 */
class ProductPolicy
{
    /** Dijalankan paling awal: admin lolos semua pengecekan. */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null; // null = lanjut ke method policy
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true; // semua user yang login boleh menjual
    }

    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->user_id || $user->hasRole('editor');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }
}
