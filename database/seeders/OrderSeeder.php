<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /** 45 pesanan dengan 1-4 item, tersebar 45 hari terakhir. */
    public function run(): void
    {
        $customers = User::where('role', 'user')->get();
        $products  = Product::all();

        foreach (range(0, 44) as $i) {
            $order = Order::factory()->for($customers[$i % $customers->count()])->create();

            foreach ($products->random(rand(1, 4)) as $product) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'name'       => $product->name,
                    'quantity'   => rand(1, 3),
                    'price'      => $product->price,
                ]);
            }
        }
    }
}
