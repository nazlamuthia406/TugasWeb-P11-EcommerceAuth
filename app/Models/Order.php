<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending'   => 'Menunggu Pembayaran',
        'paid'      => 'Dibayar',
        'shipped'   => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    /** 'status' tidak mass-assignable: hanya admin yang mengubahnya lewat controller. */
    protected $fillable = ['code', 'shipping_name', 'shipping_address'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when($status, fn (Builder $q) => $q->where('status', $status));
    }

    /** Total pesanan = jumlah (qty x harga satuan) semua item. Eager-load 'items' agar hemat query. */
    public function getTotalAttribute(): float
    {
        return (float) $this->items->sum(fn (OrderItem $i) => $i->quantity * (float) $i->price);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeAttribute(): string
    {
        return [
            'pending'   => 'warning',
            'paid'      => 'info',
            'shipped'   => 'brand',
            'completed' => 'success',
            'cancelled' => 'danger',
        ][$this->status] ?? 'gray';
    }
}
