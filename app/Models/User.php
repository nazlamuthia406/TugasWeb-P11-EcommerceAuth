<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = ['admin', 'editor', 'user'];

    /**
     * Mass assignment protection: 'role' SENGAJA tidak ada di sini,
     * sehingga tidak bisa disuntikkan lewat form register / profile.
     */
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* ---------- Relasi ---------- */

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /* ---------- Helper role ---------- */

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Admin & editor boleh mengelola semua produk. */
    public function canManageAllProducts(): bool
    {
        return $this->hasRole('admin', 'editor');
    }

    /* ---------- Accessor tampilan ---------- */

    public function getInitialsAttribute(): string
    {
        return Str::of($this->name)->explode(' ')->filter()->take(2)
            ->map(fn ($w) => Str::upper(Str::substr($w, 0, 1)))->implode('');
    }

    public function getRoleLabelAttribute(): string
    {
        return ['admin' => 'Admin', 'editor' => 'Editor', 'user' => 'Pengguna'][$this->role] ?? ucfirst((string) $this->role);
    }

    /** Tipe warna untuk <x-badge>. */
    public function getRoleBadgeAttribute(): string
    {
        return ['admin' => 'danger', 'editor' => 'info', 'user' => 'gray'][$this->role] ?? 'gray';
    }
}
