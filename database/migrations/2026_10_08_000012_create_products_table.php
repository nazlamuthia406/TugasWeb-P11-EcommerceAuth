<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Tabel 4/7: products — FK ke users (penjual) dan categories */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Penjual dihapus -> produknya ikut terhapus
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Kategori tidak boleh dihapus selama masih punya produk
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 150);
            $table->string('slug', 190)->unique();
            $table->text('description');
            $table->decimal('price', 12, 2);                // uang = decimal, bukan float
            $table->unsignedInteger('stock')->default(0);
            $table->string('emoji', 8)->default('📦');
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
