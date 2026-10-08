<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/** Bonus: demo perbandingan jumlah query lazy loading (N+1) vs eager loading. */
class EagerLoadingController extends Controller
{
    public function __invoke()
    {
        $limit = 20;

        // --- A. Lazy loading (N+1) ---
        Model::preventLazyLoading(false); // matikan pencatat N+1 sementara agar log tidak penuh
        DB::flushQueryLog();
        DB::enableQueryLog();

        $started = microtime(true);
        $lazy = Product::take($limit)->get();
        foreach ($lazy as $product) {
            $product->category->name; // +1 query per produk
            $product->user->name;     // +1 query per produk
        }
        $lazyResult = ['queries' => count(DB::getQueryLog()), 'ms' => round((microtime(true) - $started) * 1000, 1)];

        // --- B. Eager loading ---
        DB::flushQueryLog();

        $started = microtime(true);
        $eager = Product::with(['category', 'user'])->take($limit)->get();
        foreach ($eager as $product) {
            $product->category->name;
            $product->user->name;
        }
        $eagerResult = ['queries' => count(DB::getQueryLog()), 'ms' => round((microtime(true) - $started) * 1000, 1)];

        DB::disableQueryLog();
        Model::preventLazyLoading(! app()->isProduction());

        return view('admin.eager', [
            'limit' => $limit,
            'lazy'  => $lazyResult,
            'eager' => $eagerResult,
        ]);
    }
}
