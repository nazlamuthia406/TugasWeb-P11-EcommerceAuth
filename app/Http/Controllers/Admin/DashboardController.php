<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $revenue = OrderItem::whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->sum(DB::raw('quantity * price'));

        // Pendapatan 14 hari terakhir (dikelompokkan di PHP agar tidak bergantung fungsi tanggal DB)
        $days  = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->startOfDay());
        $byDay = Order::with('items')
            ->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $days->first())
            ->get()
            ->groupBy(fn (Order $o) => $o->created_at->toDateString());

        $daily = $days->map(fn ($d) => [
            'label' => $d->format('d/m'),
            'total' => $byDay->get($d->toDateString(), collect())->sum(fn (Order $o) => $o->total),
        ]);

        return view('admin.dashboard', [
            'stats' => [
                'users'    => User::count(),
                'products' => Product::count(),
                'orders'   => Order::count(),
                'revenue'  => $revenue,
            ],
            'daily'        => $daily,
            'recentOrders' => Order::with(['user', 'items'])->latest('id')->take(6)->get(),
            // withSum: total terjual per produk dihitung langsung di SQL
            'topProducts'  => Product::with('category')->withSum('orderItems as sold', 'quantity')
                ->orderByDesc('sold')->take(5)->get(),
            'categories'   => Category::withCount('products')->orderByDesc('products_count')->get(),
        ]);
    }
}
