# Dokumentasi 5 Query Tinker

Jalankan `php artisan migrate:fresh --seed`, lalu buka Tinker:

```bash
php artisan tinker
```

Salin tiap query di bawah, jalankan, dan **ambil screenshot** hasilnya ke folder `docs/screenshots/` (`tinker-1.png` … `tinker-5.png`).
Angka pada output bisa berbeda dari contoh karena data seeder bersifat acak.

---

## 1. Menghitung data (Eloquent dasar)

```php
>>> Product::count()
>>> Product::inStock()->count()      // memakai local scope
```

Tujuan: memastikan seeder menghasilkan **56 produk**, dan melihat scope `inStock()` bekerja.

## 2. Eager loading relasi belongsTo + belongsToMany

```php
>>> Product::with('category', 'tags')->first()
```

Tujuan: satu query untuk produk, satu untuk kategori, satu untuk tag → total 3 query (bukan N+1).

## 3. Relasi hasMany lewat dynamic property

```php
>>> User::has('orders')->first()->orders->count()
>>> User::where('email', 'user@example.com')->first()->products->pluck('name')
```

Tujuan: menelusuri `User → orders` dan `User → products` (FK di sisi *many*).

## 4. Agregasi di SQL: withCount & withSum

```php
>>> Order::withCount('items')->withSum('items as total_qty', 'quantity')->first()
>>> Product::withSum('orderItems as sold', 'quantity')->orderByDesc('sold')->first()
```

Tujuan: menghitung jumlah item dan total kuantitas **langsung di database**, tanpa memuat semua baris.

## 5. Query relasi + scope berantai

```php
>>> Product::whereRelation('category', 'name', 'Elektronik')->inStock()->orderBy('price')->get(['name', 'price', 'stock'])
>>> Product::withTag('terlaris')->featured()->count()
```

Tujuan: memfilter lewat relasi (`whereRelation`) dan menggabungkan beberapa local scope.

---

### Bonus: membuktikan N+1

```php
>>> DB::enableQueryLog();
>>> Product::take(10)->get()->each(fn ($p) => $p->category->name);
>>> count(DB::getQueryLog());              // 11 query (N+1)

>>> DB::flushQueryLog();
>>> Product::with('category')->take(10)->get()->each(fn ($p) => $p->category->name);
>>> count(DB::getQueryLog());              // 2 query
```
