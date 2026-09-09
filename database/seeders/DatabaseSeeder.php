<?php

namespace Database\Seeders;

use App\Models\FeaturedProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ShippingSeeder::class);

        // ---- Users ----
        $admin = User::firstOrCreate(
            ['email' => 'adminalgo@gmail.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('sCvagg*0'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'usernamealgo@gmail.com'],
            [
                'name' => 'Budiono Siregar',
                'username' => 'budi',
                'password' => Hash::make('kHabdj%88'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        $users = [$admin, $customer];
        for ($i = 0; $i < 6; $i++) {
            $users[] = User::create([
                'name' => fake()->name(),
                'username' => fake()->userName().$i,
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => fake()->randomElement(['active', 'active', 'active', 'suspended']),
            ]);
        }

        // ---- Products (Synchronized from algonation.sql) ----
        $products = [
            [
                'id' => 1,
                'name' => 'Celana Jeans',
                'category' => 'Bottoms',
                'image' => 'products/Fbc8UQUgpwvCxmiR8fbJRwMlsbQ8ZiXPk7s7LYhi.jpg',
                'description' => 'celana jeans kekinian dan terbaru 2026',
                'stock' => 8,
                'price' => 300000.00,
                'weight' => 500,
                'length' => 40,
                'width' => 30,
                'height' => 5,
                'variants' => [
                    ['Warna Putih', 5, null],
                    ['Warna Abu', 7, null],
                ],
            ],
            [
                'id' => 16,
                'name' => 'Hoodie Black mamba',
                'category' => 'Outerwear',
                'image' => 'products/MMGkdTmHX3HtdQU73bMmAkoCqlcyPaueJFXygFQ7.jpg',
                'description' => 'Hoodie Black Mamba streetwear premium',
                'stock' => 101,
                'price' => 250000.00,
                'weight' => 600,
                'length' => 40,
                'width' => 30,
                'height' => 5,
                'variants' => [],
            ],
            [
                'id' => 17,
                'name' => 'Topi Merah',
                'category' => 'Hats',
                'image' => 'products/dhDNPcGSdGvkrYOuEVIHnf0JHwwnpnvAIhpRA4cd.jpg',
                'description' => 'topi merah polos',
                'stock' => 52,
                'price' => 75000.00,
                'weight' => 300,
                'length' => 10,
                'width' => 13,
                'height' => 15,
                'variants' => [],
            ],
            [
                'id' => 19,
                'name' => 'Sepatu Kulit',
                'category' => 'Footwear',
                'image' => 'products/CPau4usMBGuNUxLdnZrqYjkdlY7h1yp4OWAIAUqJ.jpg',
                'description' => 'sepatu kulit warna hitam',
                'stock' => 98,
                'price' => 500000.00,
                'weight' => 800,
                'length' => 32,
                'width' => 20,
                'height' => 12,
                'variants' => [],
            ],
            [
                'id' => 22,
                'name' => 'Sepatu lari',
                'category' => 'Footwear',
                'image' => 'products/CSC4ir1CgWbjiyjTLsQdKz2hFnn6eUP2jlwko2Dh.jpg',
                'description' => 'Sepatu lari olahraga kasual adem dan empuk',
                'stock' => 101,
                'price' => 400000.00,
                'weight' => 750,
                'length' => 30,
                'width' => 20,
                'height' => 12,
                'variants' => [],
            ],
            [
                'id' => 23,
                'name' => 'Nike Air Jordan Wanita',
                'category' => 'Footwear',
                'image' => 'products/ucpvuE3z6uGYQeExweXKlTtZLVJifmtGdT8PNdvP.jpg',
                'description' => 'Nike Air Jordan Gorpcore Indie Sneakers Wanita white pink',
                'stock' => 97,
                'price' => 700000.00,
                'weight' => 850,
                'length' => 32,
                'width' => 22,
                'height' => 14,
                'variants' => [],
            ],
            [
                'id' => 24,
                'name' => 'Stay Bag',
                'category' => 'Bags',
                'image' => 'products/JApW0FN7fcX1esggJWOY8gNWTVBzKeZsTBvpuMpk.webp',
                'description' => 'Stay Bag Black and Brown',
                'stock' => 993,
                'price' => 1890000.00,
                'weight' => 300,
                'length' => 40,
                'width' => 30,
                'height' => 20,
                'variants' => [],
            ],
            [
                'id' => 25,
                'name' => "Berto's Hat",
                'category' => 'Hats',
                'image' => 'products/F0BSfxSn0nM0IodKu7zjgWpzbQ2EtojF51T3g5EM.webp',
                'description' => "Topi Berto's Hat edisi terbatas",
                'stock' => 109,
                'price' => 1250000.00,
                'weight' => 200,
                'length' => 20,
                'width' => 20,
                'height' => 12,
                'variants' => [],
            ],
            [
                'id' => 26,
                'name' => 'Kaos Kasual Pria',
                'category' => 'Casual T-Shirt',
                'image' => 'products/2j4rh4MDlV84hAvvH9Rrn3DY1vIvZ6CtnDKmPazm.webp',
                'description' => 'Kaos kasual pria warna putih',
                'stock' => 99,
                'price' => 75000.00,
                'weight' => 220,
                'length' => 30,
                'width' => 20,
                'height' => 2,
                'variants' => [],
            ],
            [
                'id' => 27,
                'name' => 'Kaos Kasual Pria',
                'category' => 'Casual T-Shirt',
                'image' => 'products/pNNyFcN4llcCid6fn0byzEKVyyO97EypzCctW8Tk.webp',
                'description' => 'Kaos kasual pria warna putih',
                'stock' => 100,
                'price' => 75000.00,
                'weight' => 220,
                'length' => 30,
                'width' => 20,
                'height' => 2,
                'variants' => [],
            ],
            [
                'id' => 28,
                'name' => 'Jaket The North Face',
                'category' => 'Outerwear',
                'image' => 'products/FjsxbEyu7IDrip2ezo6aU89WXLhslTyB0XkDFRbl.jpg',
                'description' => 'Jaket The North Face outdoor waterproof',
                'stock' => 101,
                'price' => 850000.00,
                'weight' => 700,
                'length' => 40,
                'width' => 30,
                'height' => 5,
                'variants' => [],
            ],
            [
                'id' => 29,
                'name' => 'Tas Coklat',
                'category' => 'Bags',
                'image' => 'products/htNA5swNtrpJT9UXmZrcibIZzJIGX10EF7Jhpwks.jpg',
                'description' => 'Tas bahu bahan kulit sintetis warna coklat',
                'stock' => 100,
                'price' => 250000.00,
                'weight' => 450,
                'length' => 35,
                'width' => 25,
                'height' => 10,
                'variants' => [],
            ],
            [
                'id' => 30,
                'name' => 'Jas Hitam',
                'category' => 'Formal Wear',
                'image' => 'products/Q4c1RfIgfiIEZI0HTeQhgbie0mxiXwujuRhHs8zX.jpg',
                'description' => 'Jas formal pria warna hitam executive',
                'stock' => 100,
                'price' => 500000.00,
                'weight' => 800,
                'length' => 45,
                'width' => 35,
                'height' => 5,
                'variants' => [],
            ],
            [
                'id' => 31,
                'name' => 'Sepatu Nike Merah',
                'category' => 'Footwear',
                'image' => 'products/SZIXgjMhAMTxU34ajEJUNd2HSVcR8zrmNSxJ7poh.jpg',
                'description' => 'Sepatu sneakers Nike edisi khusus warna merah',
                'stock' => 1002,
                'price' => 950000.00,
                'weight' => 850,
                'length' => 32,
                'width' => 22,
                'height' => 14,
                'variants' => [],
            ],
            [
                'id' => 32,
                'name' => 'Jaket Kulit Hitam',
                'category' => 'Outerwear',
                'image' => 'products/H1PpCwgLIpbSgkHhWGq8D7s9XIXGTbJCwxwPtPQS.jpg',
                'description' => 'Jaket kulit asli warna hitam pria',
                'stock' => 100,
                'price' => 500000.00,
                'weight' => 900,
                'length' => 42,
                'width' => 32,
                'height' => 6,
                'variants' => [],
            ],
        ];

        $createdProducts = [];
        foreach ($products as $p) {
            $product = Product::updateOrCreate(
                ['id' => $p['id']],
                [
                    'name' => $p['name'],
                    'category' => $p['category'],
                    'description' => $p['description'],
                    'price' => $p['price'],
                    'stock' => $p['stock'],
                    'image' => $p['image'],
                    'weight' => $p['weight'] ?? 300,
                    'length' => $p['length'] ?? 40,
                    'width' => $p['width'] ?? 30,
                    'height' => $p['height'] ?? 20,
                ]
            );

            foreach ($p['variants'] as $v) {
                ProductVariant::firstOrCreate([
                    'product_id' => $product->id,
                    'name' => $v[0],
                ], [
                    'stock' => $v[1],
                    'price' => $v[2],
                ]);
            }

            $createdProducts[] = $product;
        }

        // ---- Featured products (from algonation.sql) ----
        $featuredProductIds = [31, 30, 28, 23, 29, 22];
        foreach ($featuredProductIds as $pId) {
            if (Product::where('id', $pId)->exists()) {
                FeaturedProduct::firstOrCreate(
                    ['product_id' => $pId]
                );
            }
        }

        // ---- Flash Sales (from algonation.sql) ----
        $flashSales = [
            [
                'product_id' => 31,
                'normal_price' => 950000.00,
                'sale_price' => 499999.99,
                'discount_percentage' => 47.37,
                'stock' => 6,
                'start_at' => '2026-09-03 14:13:00',
                'end_at' => '2026-10-15 17:15:00',
                'status' => 'active',
            ],
            [
                'product_id' => 24,
                'normal_price' => 1890000.00,
                'sale_price' => 1000000.00,
                'discount_percentage' => 47.09,
                'stock' => 7,
                'start_at' => '2026-09-03 14:13:00',
                'end_at' => '2026-10-15 17:15:00',
                'status' => 'active',
            ],
            [
                'product_id' => 19,
                'normal_price' => 500000.00,
                'sale_price' => 200000.00,
                'discount_percentage' => 60.00,
                'stock' => 5,
                'start_at' => '2026-09-08 19:52:00',
                'end_at' => '2026-10-15 19:52:00',
                'status' => 'active',
            ],
        ];

        foreach ($flashSales as $fs) {
            if (Product::where('id', $fs['product_id'])->exists()) {
                \App\Models\FlashSale::updateOrCreate(
                    ['product_id' => $fs['product_id']],
                    $fs
                );
            }
        }

        // ---- Transactions ----
        for ($i = 0; $i < 12; $i++) {
            $user = fake()->randomElement($users);
            $items = fake()->numberBetween(1, 3);
            $picked = fake()->randomElements($createdProducts, $items);
            $subtotal = 0;
            $details = [];

            foreach ($picked as $product) {
                $variant = $product->variants->first();
                $price = $variant?->price ?? $product->price;
                $qty = fake()->numberBetween(1, 3);

                $details[] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'quantity' => $qty,
                    'subtotal' => $price * $qty,
                ];

                $subtotal += $price * $qty;
            }

            $shipping = $subtotal >= 500000 ? 0 : 25000;
            $status = fake()->randomElement(['pending', 'processing', 'completed', 'completed', 'completed', 'cancelled']);

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'total_price' => $subtotal,
                'shipping_cost' => $shipping,
                'payment_method' => 'midtrans',
                'shipping_address' => $user->name."\n08".fake()->numerify('##########')."\nJl. ".fake()->streetName().' No. '.fake()->numberBetween(1, 200).', '.fake()->city(),
                'status' => $status,
                'created_at' => now()->subDays(fake()->numberBetween(0, 60))->subHours(fake()->numberBetween(0, 23)),
                'updated_at' => now()->subDays(fake()->numberBetween(0, 60)),
            ]);

            foreach ($details as $d) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $d['product_id'],
                    'variant_id' => $d['variant_id'],
                    'quantity' => $d['quantity'],
                    'subtotal' => $d['subtotal'],
                ]);
            }
        }
    }
}
