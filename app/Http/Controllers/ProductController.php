<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /** GET /products — katalog publik: filter, pencarian, sorting, pagination. */
    public function index(Request $request)
    {
        $q        = $this->queryString($request, 'q');
        $category = $this->queryString($request, 'category');
        $tag      = $this->queryString($request, 'tag');
        $min      = is_numeric($request->query('min')) ? max(0, (float) $request->query('min')) : null;
        $max      = is_numeric($request->query('max')) ? max(0, (float) $request->query('max')) : null;
        $sort     = in_array($request->query('sort'), ['latest', 'price_asc', 'price_desc', 'name'], true)
            ? $request->query('sort') : 'latest';

        $products = Product::query()
            ->with(['category', 'user'])                       // eager loading
            ->search($q)
            ->inCategory($category)
            ->withTag($tag)
            ->priceBetween($min, $max)
            ->when($sort === 'price_asc', fn ($qb) => $qb->orderBy('price'))
            ->when($sort === 'price_desc', fn ($qb) => $qb->orderByDesc('price'))
            ->when($sort === 'name', fn ($qb) => $qb->orderBy('name'))
            ->when($sort === 'latest', fn ($qb) => $qb->orderByDesc('id'))
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products'   => $products,
            'categories' => Category::withCount('products')->orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
            'filters'    => compact('q', 'category', 'tag', 'min', 'max', 'sort'),
        ]);
    }

    /** GET /products/{product} — detail (route model binding via slug). */
    public function show(Product $product)
    {
        $product->load(['category', 'user', 'tags']);

        $related = Product::with(['category', 'user'])
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->inStock()
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    /** GET /manage/products — user: produk miliknya; editor/admin: semua produk. */
    public function manage(Request $request)
    {
        $user = $request->user();

        $products = Product::with(['category', 'user'])
            ->when(! $user->canManageAllProducts(), fn ($q) => $q->where('user_id', $user->id))
            ->orderByDesc('id')
            ->paginate(10);

        return view('products.manage', compact('products'));
    }

    public function create()
    {
        Gate::authorize('create', Product::class);

        return view('products.create', [
            'product'    => new Product(),
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        Gate::authorize('create', Product::class);

        $data = $request->validated();

        $product = new Product(Arr::except($data, ['tags', 'is_featured'])); // hanya kolom $fillable
        $product->user_id     = $request->user()->id;                          // pemilik = user login
        $product->is_featured = $request->user()->canManageAllProducts() && $request->boolean('is_featured');
        $product->save();
        $product->tags()->sync($data['tags'] ?? []);

        return redirect()->route('products.show', $product)->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        Gate::authorize('update', $product); // 403 jika bukan pemilik / editor / admin

        return view('products.edit', [
            'product'    => $product->load('tags'),
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);

        $data = $request->validated();

        $product->fill(Arr::except($data, ['tags', 'is_featured']));
        if ($request->user()->canManageAllProducts()) {
            $product->is_featured = $request->boolean('is_featured');
        }
        $product->save();
        $product->tags()->sync($data['tags'] ?? []);

        return redirect()->route('products.show', $product)->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        $product->delete(); // riwayat pesanan aman: order_items.product_id -> NULL (nullOnDelete)

        return redirect()->route('products.manage')->with('success', 'Produk dihapus.');
    }

    /** Ambil query string sebagai string (abaikan array / kosong). */
    private function queryString(Request $request, string $key): ?string
    {
        $value = $request->query($key);

        return is_string($value) && trim($value) !== '' ? trim($value) : null;
    }
}
