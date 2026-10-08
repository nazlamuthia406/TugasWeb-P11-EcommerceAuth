<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Tabel 7/7: order_items — FK ke orders dan products + snapshot nama & harga saat dibeli */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // Produk dihapus -> riwayat pesanan tetap ada (product_id jadi NULL)
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 150);                          // snapshot nama produk
            $table->unsignedInteger('quantity');
            $table->decimal('price', 12, 2);                      // snapshot harga satuan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
