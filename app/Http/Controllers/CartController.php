<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/** Keranjang belanja disimpan di session: ['cart' => [product_id => quantity]] */
class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart     = $request->session()->get('cart', []);
        $products = Product::with(['category', 'user'])->whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = collect($cart)
            ->filter(fn ($qty, $id) => $products->has($id))
            ->map(function ($qty, $id) use ($products) {
                $product  = $products[$id];
                $quantity = max(1, min((int) $qty, max(1, $product->stock)));

                return (object) [
                    'product'  => $product,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * (float) $product->price,
                ];
            })
            ->values();

        return view('cart.index', [
            'items' => $items,
            'total' => $items->sum('subtotal'),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['nullable', 'integer', 'min:1', 'max:99']]);

        if ($product->stock < 1) {
            return back()->with('error', 'Maaf, stok produk ini habis.');
        }

        $current = (int) $request->session()->get("cart.{$product->id}", 0);
        $request->session()->put("cart.{$product->id}", min($current + ($data['quantity'] ?? 1), $product->stock));

        return back()->with('success', "{$product->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);

        $request->session()->put("cart.{$product->id}", min($data['quantity'], max(1, $product->stock)));

        return back()->with('success', 'Jumlah diperbarui.');
    }

    public function destroy(Request $request, Product $product)
    {
        $request->session()->forget("cart.{$product->id}");

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
