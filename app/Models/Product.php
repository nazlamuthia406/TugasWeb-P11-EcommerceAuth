<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * 'user_id', 'slug' dan 'is_featured' sengaja TIDAK mass-assignable:
     * pemilik diambil dari user yang login, slug dibuat otomatis,
     * dan is_featured hanya boleh diubah admin/editor (diatur di controller).
     */
    protected $fillable = ['category_id', 'name', 'description', 'price', 'stock', 'emoji'];

    protected function casts(): array
    {
        return [
            'price'       => 'decimal:2',
            'stock'       => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Slug unik dibuat otomatis saat produk dibuat
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    /** URL produk memakai slug: /products/laptop-asus-vivobook-14-x7k2a */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /* ---------- Relasi ---------- */

    public function user(): BelongsTo // penjual
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /* ---------- Local scopes ---------- */

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn (Builder $q) => $q->where(function (Builder $w) use ($term) {
            $w->where('name', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%");
        }));
    }

    public function scopeInCategory(Builder $query, ?string $slug): Builder
    {
        return $query->when($slug, fn (Builder $q) => $q->whereRelation('category', 'slug', $slug));
    }

    public function scopeWithTag(Builder $query, ?string $slug): Builder
    {
        return $query->when($slug, fn (Builder $q) => $q->whereRelation('tags', 'slug', $slug));
    }

    public function scopePriceBetween(Builder $query, ?float $min, ?float $max): Builder
    {
        return $query
            ->when($min !== null, fn (Builder $q) => $q->where('price', '>=', $min))
            ->when($max !== null, fn (Builder $q) => $q->where('price', '<=', $max));
    }
}
