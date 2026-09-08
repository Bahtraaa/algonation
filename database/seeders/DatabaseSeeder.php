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
            ['email' => 'admin@algonation.com'],
            [
                'name' => 'Admin ALGO NATION',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@algonation.com'],
            [
                'name' => 'Budi Santoso',
                'username' => 'budi',
                'password' => Hash::make('password'),
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

        // ---- Products ----
        $products = [
            ['name' => 'Lemari Pakaian Minimalis 3 Pintu', 'category' => 'Lemari', 'price' => 2450000, 'stock' => 12, 'description' => 'Lemari pakaian minimalis dengan 3 pintu sliding, material HDF berkualitas, cocok untuk kamar tidur modern.', 'variants' => [['Warna Putih', 5, null], ['Warna Coklat', 7, null]]],
            ['name' => 'Lemari TV Rack Kayu Jati', 'category' => 'Lemari', 'price' => 1850000, 'stock' => 8, 'description' => 'Rak TV dari kayu jati dengan finishing natural, memiliki laci dan rak penyimpanan.', 'variants' => []],

            ['name' => 'Kursi Kantor Ergonomis', 'category' => 'Kursi', 'price' => 899000, 'stock' => 20, 'description' => 'Kursi kantor ergonomis dengan sandaran jaring, penyangga lumbar, dan tinggi adjustable.', 'variants' => [['Hitam', 10, 899000], ['Biru', 6, 949000], ['Abu-abu', 4, 899000]]],
            ['name' => 'Kurzsi Makan Kayu Set 4', 'category' => 'Kursi', 'price' => 1250000, 'stock' => 6, 'description' => 'Set 4 kursi makan dari kayu solid dengan bantalan dudukan empuk.', 'variants' => []],

            ['name' => 'Meja Makan Kayu 6 Kursi', 'category' => 'Meja', 'price' => 3250000, 'stock' => 5, 'description' => 'Meja makan besar untuk 6 orang dari kayu mahoni solid, cocok untuk ruang makan keluarga.', 'variants' => []],
            ['name' => 'Meja Belajar Minimalis', 'category' => 'Meja', 'price' => 649000, 'stock' => 25, 'description' => 'Meja belajar minimalis dengan rak buku dan laci, ukuran compact untuk kamar anak.', 'variants' => [['Putih', 15, 649000], ['Coklat', 10, 599000]]],

            ['name' => 'Piring Keramik Premium Set 6', 'category' => 'Piring', 'price' => 249000, 'stock' => 40, 'description' => 'Set 6 piring keramik premium ukuran 10 inci, microwave & dishwasher safe.', 'variants' => [['Motif Bunga', 20, 249000], ['Polos Putih', 20, 229000]]],
            ['name' => 'Piring Makan Melamin Set 12', 'category' => 'Piring', 'price' => 189000, 'stock' => 35, 'description' => 'Set 12 piring melamin anti pecah, cocok untuk restoran dan rumah tangga.', 'variants' => []],

            ['name' => 'Mangkuk Sup Keramik Set 4', 'category' => 'Mangkuk', 'price' => 159000, 'stock' => 30, 'description' => 'Mangkuk sup keramik elegant set 4, kapasitas 500ml, aman untuk microwave.', 'variants' => []],

            ['name' => 'Gelas Kaca Kopi Set 6', 'category' => 'Gelas', 'price' => 129000, 'stock' => 50, 'description' => 'Gelas kaca tebal untuk kopi atau teh, set 6 dengan kapasitas 300ml.', 'variants' => [['Bening', 30, 129000], ['Hijau', 20, 139000]]],

            ['name' => 'Sendok Stainless Set 12', 'category' => 'Sendok', 'price' => 99000, 'stock' => 60, 'description' => 'Sendok makan stainless steel berkualitas, anti karat, set 12 pcs.', 'variants' => []],

            ['name' => 'Garpu Stainless Set 12', 'category' => 'Garpu', 'price' => 99000, 'stock' => 60, 'description' => 'Garpu makan stainless steel berkualitas, anti karat, set 12 pcs.', 'variants' => []],

            ['name' => 'Meja Rias Minimalis', 'category' => 'Meja', 'price' => 1150000, 'stock' => 3, 'description' => 'Meja rias minimalis dengan cermin dan lampu LED.', 'variants' => []],
            ['name' => 'Kursi Sofa Santai', 'category' => 'Kursi', 'price' => 799000, 'stock' => 2, 'description' => 'Kursi sofa santai dengan bahan kain beludru premium.', 'variants' => [['Merah', 1, 799000], ['Biru Navy', 1, 849000]]],
            ['name' => 'Set Piring & Mangkuk Keramik', 'category' => 'Piring', 'price' => 329000, 'stock' => 4, 'description' => 'Paket komplit piring dan mangkuk keramik untuk kebutuhan sehari-hari.', 'variants' => []],
        ];

        $createdProducts = [];
        foreach ($products as $p) {
            $product = Product::create([
                'name' => $p['name'],
                'category' => $p['category'],
                'description' => $p['description'],
                'price' => $p['price'],
                'stock' => $p['stock'],
                'image' => null,
                'weight' => $p['weight'] ?? 300,
                'length' => $p['length'] ?? 40,
                'width' => $p['width'] ?? 30,
                'height' => $p['height'] ?? 20,
            ]);

            foreach ($p['variants'] as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v[0],
                    'stock' => $v[1],
                    'price' => $v[2],
                ]);
            }

            $createdProducts[] = $product;
        }

        // ---- Featured products (reference existing products only) ----
        foreach ($createdProducts as $index => $product) {
            if ($index >= 4) {
                break;
            }

            FeaturedProduct::firstOrCreate(['product_id' => $product->id]);
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
