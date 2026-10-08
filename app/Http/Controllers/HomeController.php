<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'categories' => Category::withCount('products')->orderBy('name')->get(),
            // Eager loading: with() mencegah N+1 saat kartu produk menampilkan kategori & penjual
            'featured'   => Product::with(['category', 'user'])->featured()->inStock()->latest('id')->take(8)->get(),
            'latest'     => Product::with(['category', 'user'])->inStock()->latest('id')->take(8)->get(),
            'stats'      => [
                'products' => Product::count(),
                'sellers'  => User::has('products')->count(),
                'orders'   => Order::count(),
            ],
        ]);
    }
}
