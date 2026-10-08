<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $spent = OrderItem::whereHas('order', fn ($q) => $q->where('user_id', $user->id)->where('status', '!=', 'cancelled'))
            ->sum(DB::raw('quantity * price'));

        return view('dashboard', [
            'orders'      => $user->orders()->with('items')->latest('id')->take(4)->get(),
            'myProducts'  => $user->products()->with('category')->latest('id')->take(4)->get(),
            'stats'       => [
                'orders'      => $user->orders()->count(),
                'spent'       => $spent,
                'my_products' => $user->products()->count(),
                'all_products' => Product::count(),
            ],
        ]);
    }
}
