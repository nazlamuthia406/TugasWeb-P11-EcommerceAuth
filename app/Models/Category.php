<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'color'];

    public const GRADIENTS = [
        'indigo'  => 'from-indigo-500 to-blue-500',
        'rose'    => 'from-rose-500 to-pink-500',
        'amber'   => 'from-amber-400 to-orange-500',
        'emerald' => 'from-emerald-500 to-teal-500',
        'violet'  => 'from-violet-500 to-purple-600',
        'fuchsia' => 'from-fuchsia-500 to-pink-500',
        'orange'  => 'from-orange-500 to-red-500',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getGradientAttribute(): string
    {
        return self::GRADIENTS[$this->color] ?? self::GRADIENTS['indigo'];
    }
}
