<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Terlaris', 'Diskon', 'Baru', 'Gratis Ongkir', 'Garansi Resmi', 'Produk Lokal', 'Eco-friendly', 'Hemat'] as $name) {
            Tag::create(['name' => $name, 'slug' => Str::slug($name)]);
        }
    }
}
