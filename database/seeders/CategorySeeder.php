<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Elektronik',          '💻', 'indigo'],
            ['Fashion',             '👕', 'rose'],
            ['Rumah Tangga',        '🏠', 'amber'],
            ['Olahraga',            '⚽', 'emerald'],
            ['Buku & Alat Tulis',   '📚', 'violet'],
            ['Kecantikan',          '💄', 'fuchsia'],
            ['Makanan & Minuman',   '☕', 'orange'],
        ];

        foreach ($categories as [$name, $icon, $color]) {
            Category::create([
                'name'  => $name,
                'slug'  => Str::slug($name),
                'icon'  => $icon,
                'color' => $color,
            ]);
        }
    }
}
