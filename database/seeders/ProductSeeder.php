<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /** 7 kategori x 8 produk = 56 produk dengan nama, harga & deskripsi yang realistis. */
    private function catalog(): array
    {
        return [
            'Elektronik' => [
                ['Laptop ASUS Vivobook 14 (i5, 16GB, 512GB SSD)', 8999000, '💻', 'Laptop tipis 14 inci dengan SSD 512GB dan RAM 16GB, cocok untuk kuliah, ngoding, dan kerja harian.'],
                ['Smartphone Samsung Galaxy A35 128GB',            4799000, '📱', 'Layar Super AMOLED 6,6 inci, kamera 50MP, dan baterai 5000mAh yang awet seharian.'],
                ['Headphone Bluetooth ANC Soundcore Q30',           849000, '🎧', 'Active noise cancelling hingga 40dB dengan baterai 40 jam, nyaman dipakai belajar maupun bepergian.'],
                ['Smartwatch Xiaomi Redmi Watch 4',                1099000, '⌚', 'Layar AMOLED 1,97 inci, GPS built-in, dan lebih dari 150 mode olahraga.'],
                ['Powerbank Anker 10000mAh PD 20W',                 349000, '🔋', 'Ringkas dan bertenaga, mendukung pengisian cepat PD 20W untuk ponsel dan tablet.'],
                ['Keyboard Mechanical Keychron K2',                1299000, '⌨️', 'Keyboard 75% wireless dengan hot-swappable switch dan backlight RGB.'],
                ['Mouse Wireless Logitech M331 Silent',             189000, '🖱️', 'Klik senyap, tracking presisi, dan baterai hingga 24 bulan.'],
                ['Monitor LG UltraGear 24" 144Hz',                 2199000, '🖥️', 'Panel IPS 1ms dengan refresh rate 144Hz, mulus untuk gaming dan desain.'],
            ],
            'Fashion' => [
                ['Kemeja Flanel Pria Lengan Panjang',               189000, '👕', 'Bahan katun flanel lembut, motif kotak klasik, nyaman untuk kuliah atau nongkrong.'],
                ['Dress Midi Linen Wanita',                         259000, '👗', 'Potongan A-line dari linen adem, jatuh dan tidak menerawang.'],
                ['Sneakers Putih Casual Unisex',                    349000, '👟', 'Sol empuk anti selip dengan desain minimalis yang cocok dipadukan dengan outfit apa pun.'],
                ['Jaket Denim Oversize',                            329000, '🧥', 'Denim tebal dengan potongan oversize yang kekinian, tersedia ukuran M–XL.'],
                ['Tas Ransel Laptop Anti Air 25L',                  279000, '🎒', 'Muat laptop hingga 15,6 inci, port USB pengisian, dan bahan water-resistant.'],
                ['Topi Baseball Polos',                              89000, '🧢', 'Bahan twill premium dengan strap belakang yang dapat disesuaikan.'],
                ['Kacamata Hitam Polarized',                        159000, '🕶️', 'Lensa polarized anti silau dengan proteksi UV400.'],
                ['Jam Tangan Analog Minimalis',                     399000, '⌚', 'Case stainless 40mm, strap kulit, tahan percikan air.'],
            ],
            'Rumah Tangga' => [
                ['Rice Cooker Digital 1,8 Liter',                   549000, '🍚', 'Delapan menu memasak otomatis, inner pot anti lengket, dan fitur keep warm 12 jam.'],
                ['Air Fryer Digital 4 Liter',                       699000, '🍟', 'Masak renyah dengan sedikit minyak, panel sentuh dengan 8 preset menu.'],
                ['Set Panci Anti Lengket 5 Pcs',                    449000, '🍳', 'Lapisan granit anti lengket, aman untuk kompor gas dan induksi.'],
                ['Vacuum Cleaner Handheld Cordless',                599000, '🧹', 'Daya hisap kuat, baterai isi ulang, ringan untuk membersihkan kasur dan mobil.'],
                ['Lampu Meja LED Dimmable',                         129000, '💡', 'Tiga mode warna cahaya dengan kecerahan yang dapat diatur, ramah untuk mata.'],
                ['Dispenser Air Galon Bawah',                      1199000, '🚰', 'Air panas dan dingin, galon di bawah sehingga mudah diganti tanpa mengangkat berat.'],
                ['Set Sprei Katun 160x200',                         299000, '🛏️', 'Katun 100% yang adem dan lembut, termasuk 2 sarung bantal.'],
                ['Rak Sepatu Susun 4 Tingkat',                      169000, '🗄️', 'Rangka kokoh, mudah dirakit, muat hingga 12 pasang sepatu.'],
            ],
            'Olahraga' => [
                ['Sepatu Lari Ringan Pria',                         599000, '🏃', 'Midsole empuk dan upper breathable, ringan untuk lari harian maupun jarak jauh.'],
                ['Matras Yoga Anti Slip 6mm',                       149000, '🧘', 'Permukaan anti slip dengan bantalan 6mm yang nyaman untuk lutut dan punggung.'],
                ['Dumbbell Adjustable 10kg (Sepasang)',             389000, '🏋️', 'Beban dapat disesuaikan, grip karet anti selip, cocok untuk latihan di rumah.'],
                ['Bola Futsal Size 4',                              179000, '⚽', 'Jahitan mesin dengan lapisan PU yang awet, pantulan stabil di lapangan indoor.'],
                ['Raket Badminton Carbon Set 2 Pcs',                259000, '🏸', 'Frame carbon ringan lengkap dengan tas dan grip, siap main.'],
                ['Botol Minum Stainless 750ml',                     119000, '🥤', 'Menjaga suhu dingin 24 jam dan panas 12 jam, bebas BPA.'],
                ['Sepeda Lipat 20" 7 Speed',                       2999000, '🚲', 'Rangka alloy ringan, mudah dilipat, ideal untuk komuter harian.'],
                ['Tenda Camping Kapasitas 4 Orang',                 849000, '⛺', 'Double layer anti hujan, pemasangan cepat, dilengkapi ventilasi udara.'],
            ],
            'Buku & Alat Tulis' => [
                ['Panduan Laravel untuk Pemula',                     99000, '📘', 'Belajar Laravel dari nol: routing, Blade, Eloquent, hingga autentikasi dengan proyek nyata.'],
                ['HTML, CSS & JavaScript Modern',                   109000, '📗', 'Fondasi pengembangan web dengan contoh kode dan latihan di setiap bab.'],
                ['Notebook A5 Dotted Hardcover',                     59000, '📓', 'Kertas 100gsm anti tembus, 192 halaman, cocok untuk bullet journal.'],
                ['Set Pulpen Gel 12 Warna',                          49000, '🖊️', 'Tinta cepat kering dan halus, ujung 0,5mm untuk catatan yang rapi.'],
                ['Highlighter Pastel Set 6 Warna',                   45000, '🖍️', 'Warna pastel lembut, tidak tembus ke halaman belakang.'],
                ['Kalkulator Ilmiah 417 Fungsi',                    249000, '🧮', 'Mendukung statistik, matriks, dan persamaan; favorit mahasiswa teknik.'],
                ['Planner Mingguan 2027',                            79000, '🗓️', 'Tata letak mingguan dengan ruang target dan habit tracker.'],
                ['Rak Buku Meja Kayu Minimalis',                    189000, '📚', 'Rak dua tingkat dari kayu MDF yang kokoh, hemat ruang di meja belajar.'],
            ],
            'Kecantikan' => [
                ['Serum Vitamin C 20ml',                            129000, '🧴', 'Membantu mencerahkan dan menyamarkan noda hitam, tekstur ringan cepat meresap.'],
                ['Sunscreen SPF 50 PA++++ 50ml',                     89000, '☀️', 'Perlindungan tinggi dari UVA/UVB, finish natural tanpa white cast.'],
                ['Facial Wash Gentle 100ml',                         55000, '🫧', 'Membersihkan tanpa membuat kulit kering, cocok untuk kulit sensitif.'],
                ['Lipstik Matte Velvet',                             79000, '💄', 'Warna pekat tahan lama dengan tekstur ringan di bibir.'],
                ['Moisturizer Gel Ceramide 50ml',                   119000, '🧴', 'Menjaga skin barrier dan melembapkan hingga 24 jam.'],
                ['Masker Wajah Sheet (10 Pcs)',                      65000, '🎭', 'Kaya hyaluronic acid untuk kulit lembap dan segar setelah beraktivitas.'],
                ['Parfum Floral Musk 50ml',                         249000, '🌸', 'Aroma floral musk yang lembut dan tahan hingga 6 jam.'],
                ['Hair Dryer Ionic 1200W',                          289000, '💇', 'Teknologi ion mengurangi rambut kusut, tiga level panas dan dua kecepatan.'],
            ],
            'Makanan & Minuman' => [
                ['Kopi Arabika Mandailing 250g',                     89000, '☕', 'Biji kopi Sumatera Utara sangrai medium, aroma cokelat dan rempah yang pekat.'],
                ['Teh Hijau Premium (20 Kantong)',                   39000, '🍵', 'Daun teh pilihan dengan rasa segar dan sedikit manis alami.'],
                ['Madu Hutan Murni 500ml',                          119000, '🍯', 'Madu murni tanpa campuran gula, dipanen langsung dari petani lokal.'],
                ['Granola Madu Kacang 400g',                         69000, '🥣', 'Renyah dan tidak terlalu manis, pas untuk sarapan bersama yogurt.'],
                ['Keripik Singkong Balado 250g',                     29000, '🌶️', 'Renyah gurih dengan bumbu balado pedas manis khas Sumatera.'],
                ['Bika Ambon Medan Original',                        79000, '🍰', 'Kue legendaris Medan dengan tekstur sarang lebah yang lembut dan wangi pandan.'],
                ['Sambal Teri Medan Botol 200g',                     45000, '🫙', 'Sambal teri pedas gurih, teman sempurna untuk nasi hangat.'],
                ['Cokelat Dark 70% 100g',                            42000, '🍫', 'Cokelat pekat dengan rasa pahit seimbang, dibuat dari kakao pilihan.'],
            ],
        ];
    }

    public function run(): void
    {
        $categories = Category::all()->keyBy('name');
        $tags       = Tag::all();

        // Penjual: akun demo (user, editor, admin) + beberapa pengguna acak
        $sellers = collect([
            User::where('email', 'user@example.com')->first(),
            User::where('email', 'editor@example.com')->first(),
            User::where('email', 'admin@example.com')->first(),
        ])->merge(User::where('role', 'user')->where('email', '!=', 'user@example.com')->take(5)->get());

        $i = 0;
        foreach ($this->catalog() as $categoryName => $items) {
            $category = $categories[$categoryName];

            foreach ($items as [$name, $price, $emoji, $description]) {
                $product = Product::factory()
                    ->for($category)
                    ->for($sellers[$i % $sellers->count()], 'user')
                    ->create([
                        'name'        => $name,
                        'price'       => $price,
                        'emoji'       => $emoji,
                        'description' => $description,
                        'stock'       => fake()->numberBetween(8, 150),
                        'is_featured' => $i % 5 === 0,
                    ]);

                // 1-3 tag acak per produk (tabel pivot product_tag)
                $product->tags()->sync($tags->random(rand(1, 3))->pluck('id'));
                $i++;
            }
        }

        // Beberapa produk dibuat habis agar tampilan "Stok habis" bisa diuji
        Product::inRandomOrder()->limit(3)->update(['stock' => 0]);
    }
}
