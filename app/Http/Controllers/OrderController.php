<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items')->latest('id')->paginate(8);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order); // pemilik atau admin saja

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    /** POST /checkout — ubah isi keranjang menjadi pesanan (atomic dengan transaction). */
    public function store(CheckoutRequest $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        try {
            $order = DB::transaction(function () use ($request, $cart) {
                // lockForUpdate: cegah dua pembeli mengambil stok terakhir bersamaan
                $products = Product::whereIn('id', array_keys($cart))->lockForUpdate()->get()->keyBy('id');

                $order = $request->user()->orders()->create([
                    'code'             => 'INV-' . now()->format('ymd') . '-' . Str::upper(Str::random(5)),
                    'shipping_name'    => $request->validated('shipping_name'),
                    'shipping_address' => $request->validated('shipping_address'),
                ]);

                foreach ($cart as $productId => $quantity) {
                    $product = $products->get($productId);

                    if (! $product || $product->stock < $quantity) {
                        throw new \RuntimeException('Stok "' . ($product->name ?? 'produk') . '" tidak mencukupi.');
                    }

                    $order->items()->create([
                        'product_id' => $product->id,
                        'name'       => $product->name,
                        'quantity'   => $quantity,
                        'price'      => $product->price,
                    ]);

                    $product->decrement('stock', $quantity);
                }

                return $order;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        $request->session()->forget('cart');

        return redirect()->route('orders.show', $order)->with('success', "Pesanan {$order->code} berhasil dibuat!");
    }
}
