<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Tabel 5/7: product_tag — pivot many-to-many products <-> tags */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_tag', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['product_id', 'tag_id']); // satu pasangan hanya sekali
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_tag');
    }
};
