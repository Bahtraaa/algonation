<?php

namespace Database\Seeders;

use App\Models\FeaturedProduct;
use App\Models\FlashSale;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Data di bawah adalah data referensi/master yang diselaraskan dengan
     * dump database produksi (`algonation (1).sql`): users, products,
     * product_variants, featured_products, flash_sales, dan (via
     * ShippingSeeder) seluruh konfigurasi pengiriman.
     *
     * Data operasional (transactions, transaction_details, addresses)
     * TIDAK di-seed karena dibuat lewat alur aplikasi (checkout, kelola
     * alamat) dan berisi data milik user asli.
     */
    public function run(): void
    {
        $this->call(ShippingSeeder::class);

        // ---- Users (diselaraskan dengan `algonation (1).sql`) ----
        // Password di-hash saat seeding; hash bcrypt selalu berbeda setiap
        // generate (salt acak) sehingga yang disamakan adalah plaintext-nya:
        // admin => sCvagg*0, budi => kHabdj%88, sisanya => password.
        $users = [
            ['id' => 1, 'name' => 'Admin', 'username' => 'admin', 'email' => 'adminalgo@gmail.com', 'role' => 'admin', 'status' => 'active', 'password' => Hash::make('sCvagg*0'), 'created_at' => '2026-09-08 19:12:32'],
            ['id' => 2, 'name' => 'Budiono Siregar', 'username' => 'budi', 'email' => 'usernamealgo@gmail.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('kHabdj%88'), 'created_at' => '2026-09-08 19:12:33'],
            ['id' => 3, 'name' => 'Katherine Luettgen', 'username' => 'art830', 'email' => 'rosemarie.green@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:34'],
            ['id' => 4, 'name' => 'Sandy Walker Jr.', 'username' => 'rosenbaum.clemmie1', 'email' => 'gilda.smith@example.net', 'role' => 'user', 'status' => 'suspended', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:35'],
            ['id' => 5, 'name' => 'Ena Dach', 'username' => 'lkohler2', 'email' => 'tristin.west@example.net', 'role' => 'user', 'status' => 'suspended', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:35'],
            ['id' => 6, 'name' => 'Maverick Upton', 'username' => 'ursula763', 'email' => 'vankunding@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:36'],
            ['id' => 7, 'name' => 'Clare Lowe', 'username' => 'laney484', 'email' => 'emard.rosella@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:36'],
            ['id' => 8, 'name' => 'Prof. Rafael McCullough', 'username' => 'zander.thiel5', 'email' => 'kelvin12@example.net', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:37'],
            ['id' => 9, 'name' => 'Isom Schumm', 'username' => 'cole.kuphal0', 'email' => 'mark.hodkiewicz@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:55'],
            ['id' => 10, 'name' => 'Ozella Kris', 'username' => 'kody921', 'email' => 'oswaniawski@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:55'],
            ['id' => 11, 'name' => 'Brennan Schultz', 'username' => 'marcelle592', 'email' => 'yasmeen.dickinson@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:56'],
            ['id' => 12, 'name' => 'Domenick Bashirian', 'username' => 'ewell.lindgren3', 'email' => 'elwyn09@example.org', 'role' => 'user', 'status' => 'suspended', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:56'],
            ['id' => 13, 'name' => 'Dr. Aliza Veum II', 'username' => 'braun.stephany4', 'email' => 'srenner@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:57'],
            ['id' => 14, 'name' => 'Genevieve Daugherty PhD', 'username' => 'kris.tressie5', 'email' => 'zack47@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:12:57'],
            ['id' => 15, 'name' => 'Prof. Aileen Nienow III', 'username' => 'schamberger.alice0', 'email' => 'gottlieb.aliyah@example.net', 'role' => 'user', 'status' => 'suspended', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:20:40'],
            ['id' => 16, 'name' => 'Mr. Dalton Bernier Sr.', 'username' => 'demarco961', 'email' => 'freeda.donnelly@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:20:41'],
            ['id' => 17, 'name' => 'Paula Howe', 'username' => 'annette.yundt2', 'email' => 'pledner@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:20:41'],
            ['id' => 18, 'name' => 'Ms. Susan Langworth V', 'username' => 'hrogahn3', 'email' => 'kihn.manuela@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:20:41'],
            ['id' => 19, 'name' => 'Avery Nolan', 'username' => 'flo.paucek4', 'email' => 'mervin.sporer@example.net', 'role' => 'user', 'status' => 'suspended', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:20:42'],
            ['id' => 20, 'name' => 'Matt Franecki', 'username' => 'elody385', 'email' => 'marquardt.jazlyn@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:20:42'],
            ['id' => 21, 'name' => 'Miss Romaine Powlowski', 'username' => 'schimmel.hudson0', 'email' => 'makayla16@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:11'],
            ['id' => 22, 'name' => 'Ida Mraz', 'username' => 'gschamberger1', 'email' => 'lia37@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:11'],
            ['id' => 23, 'name' => 'Prof. Junius Schroeder III', 'username' => 'anais032', 'email' => 'vblock@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:12'],
            ['id' => 24, 'name' => 'Tito Rutherford', 'username' => 'clinton593', 'email' => 'xkoepp@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:12'],
            ['id' => 25, 'name' => 'Prof. Ludie Stokes II', 'username' => 'labadie.lelah4', 'email' => 'burley98@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:13'],
            ['id' => 26, 'name' => 'Miracle Morissette', 'username' => 'jmclaughlin5', 'email' => 'stokes.cynthia@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:13'],
            ['id' => 27, 'name' => 'Dr. Ewell Mayert V', 'username' => 'leffler.agustin0', 'email' => 'drew.bednar@example.net', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:34'],
            ['id' => 28, 'name' => 'Mr. Laverna Ebert', 'username' => 'susan.hoeger1', 'email' => 'ankunding.gilberto@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:35'],
            ['id' => 29, 'name' => 'Giovanny Pagac Sr.', 'username' => 'dbogisich2', 'email' => 'kilback.cornelius@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:35'],
            ['id' => 30, 'name' => 'Angie Stroman', 'username' => 'mills.lance3', 'email' => 'gschiller@example.net', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:36'],
            ['id' => 31, 'name' => 'Newell Hermiston', 'username' => 'scole4', 'email' => 'upagac@example.com', 'role' => 'user', 'status' => 'suspended', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:36'],
            ['id' => 32, 'name' => 'Mr. Jace Dickens DDS', 'username' => 'hassan.stanton5', 'email' => 'kuhn.arch@example.net', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:21:37'],
            ['id' => 33, 'name' => 'bahtra', 'username' => 'bahtra', 'email' => 'bhatrabata2@gmail.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 19:40:25'],
            ['id' => 35, 'name' => 'Dr. Julius Feeney', 'username' => 'owiegand0', 'email' => 'jakubowski.rex@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 23:31:15'],
            ['id' => 36, 'name' => 'Mr. Brian Harvey', 'username' => 'murray.reilly1', 'email' => 'dhirthe@example.net', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 23:31:15'],
            ['id' => 37, 'name' => 'Mr. Kale Waelchi V', 'username' => 'becker.suzanne2', 'email' => 'mdavis@example.net', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 23:31:16'],
            ['id' => 38, 'name' => 'Tristian Glover', 'username' => 'ferry.anastacio3', 'email' => 'osborne.bruen@example.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 23:31:16'],
            ['id' => 39, 'name' => 'Prof. Elbert Langworth', 'username' => 'bernice084', 'email' => 'henry69@example.org', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 23:31:17'],
            ['id' => 40, 'name' => 'Yessenia Pfeffer', 'username' => 'lora.waters5', 'email' => 'ggraham@example.com', 'role' => 'user', 'status' => 'suspended', 'password' => Hash::make('password'), 'created_at' => '2026-09-08 23:31:17'],
            ['id' => 43, 'name' => 'gssggsg hddhhddhhd', 'username' => 'dhud', 'email' => 'priatuaqw@gmail.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-10 06:21:40'],
            ['id' => 44, 'name' => 'gssggsg hddhhddhhd', 'username' => 'dhdhd', 'email' => 'ewjebdjbjw1wjdj@gmail.com', 'role' => 'user', 'status' => 'active', 'password' => Hash::make('password'), 'created_at' => '2026-09-13 20:03:54'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['id' => $user['id']],
                [
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'status' => $user['status'],
                    'password' => $user['password'],
                    'created_at' => $user['created_at'],
                    'updated_at' => $user['created_at'],
                ]
            );
        }

        // ---- Products (diselaraskan dengan `algonation (1).sql`) ----
        $products = [
            [
                'id' => 1,
                'name' => 'Celana Jeans',
                'category' => 'Bottoms',
                'status' => 'active',
                'image' => 'images/products/Fbc8UQUgpwvCxmiR8fbJRwMlsbQ8ZiXPk7s7LYhi.jpg',
                'description' => 'celana jeans kekinian dan terbaru 2026',
                'stock' => 8,
                'price' => 300000.00,
                'weight' => 500,
                'length' => 40,
                'width' => 30,
                'height' => 5,
                'created_at' => '2026-09-08 19:12:37',
            ],
            [
                'id' => 16,
                'name' => 'Hoodie Black mamba',
                'category' => 'Outerwear',
                'status' => 'active',
                'image' => 'images/products/MMGkdTmHX3HtdQU73bMmAkoCqlcyPaueJFXygFQ7.jpg',
                'description' => 'Hoodie Black Mamba streetwear premium',
                'stock' => 101,
                'price' => 250000.00,
                'weight' => 600,
                'length' => 40,
                'width' => 30,
                'height' => 5,
                'created_at' => '2026-09-08 19:12:37',
            ],
            [
                'id' => 17,
                'name' => 'Topi Merah',
                'category' => 'Hats',
                'status' => 'active',
                'image' => 'images/products/dhDNPcGSdGvkrYOuEVIHnf0JHwwnpnvAIhpRA4cd.jpg',
                'description' => 'topi merah polos',
                'stock' => 52,
                'price' => 75000.00,
                'weight' => 300,
                'length' => 10,
                'width' => 13,
                'height' => 15,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 19,
                'name' => 'Sepatu Kulit',
                'category' => 'Footwear',
                'status' => 'active',
                'image' => 'images/products/CPau4usMBGuNUxLdnZrqYjkdlY7h1yp4OWAIAUqJ.jpg',
                'description' => 'sepatu kulit warna hitam',
                'stock' => 98,
                'price' => 500000.00,
                'weight' => 800,
                'length' => 32,
                'width' => 20,
                'height' => 12,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 22,
                'name' => 'Sepatu lari',
                'category' => 'Footwear',
                'status' => 'active',
                'image' => 'images/products/CSC4ir1CgWbjiyjTLsQdKz2hFnn6eUP2jlwko2Dh.jpg',
                'description' => 'Sepatu lari olahraga kasual adem dan empuk',
                'stock' => 101,
                'price' => 400000.00,
                'weight' => 750,
                'length' => 30,
                'width' => 20,
                'height' => 12,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 23,
                'name' => 'Nike Air Jordan Wanita',
                'category' => 'Footwear',
                'status' => 'active',
                'image' => 'images/products/ucpvuE3z6uGYQeExweXKlTtZLVJifmtGdT8PNdvP.jpg',
                'description' => 'Nike Air Jordan Gorpcore Indie Sneakers Wanita white pink',
                'stock' => 98,
                'price' => 700000.00,
                'weight' => 850,
                'length' => 32,
                'width' => 22,
                'height' => 14,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 24,
                'name' => 'Stay Bag',
                'category' => 'Bags',
                'status' => 'active',
                'image' => 'images/products/JApW0FN7fcX1esggJWOY8gNWTVBzKeZsTBvpuMpk.webp',
                'description' => 'Stay Bag Black and Brown',
                'stock' => 993,
                'price' => 1890000.00,
                'weight' => 300,
                'length' => 40,
                'width' => 30,
                'height' => 20,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 25,
                'name' => "Berto's Hat",
                'category' => 'Hats',
                'status' => 'active',
                'image' => 'images/products/F0BSfxSn0nM0IodKu7zjgWpzbQ2EtojF51T3g5EM.webp',
                'description' => "Topi Berto's Hat edisi terbatas",
                'stock' => 109,
                'price' => 1250000.00,
                'weight' => 200,
                'length' => 20,
                'width' => 20,
                'height' => 12,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 26,
                'name' => 'Kaos Kasual Pria',
                'category' => 'Casual T-Shirt',
                'status' => 'active',
                'image' => 'images/products/2j4rh4MDlV84hAvvH9Rrn3DY1vIvZ6CtnDKmPazm.webp',
                'description' => 'Kaos kasual pria warna putih',
                'stock' => 99,
                'price' => 75000.00,
                'weight' => 220,
                'length' => 30,
                'width' => 20,
                'height' => 2,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 27,
                'name' => 'Kaos Kasual Pria',
                'category' => 'Casual T-Shirt',
                'status' => 'active',
                'image' => 'images/products/pNNyFcN4llcCid6fn0byzEKVyyO97EypzCctW8Tk.webp',
                'description' => 'Kaos kasual pria warna putih',
                'stock' => 100,
                'price' => 75000.00,
                'weight' => 220,
                'length' => 30,
                'width' => 20,
                'height' => 2,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 28,
                'name' => 'Jaket The North Face',
                'category' => 'Outerwear',
                'status' => 'active',
                'image' => 'images/products/FjsxbEyu7IDrip2ezo6aU89WXLhslTyB0XkDFRbl.jpg',
                'description' => 'Jaket The North Face outdoor waterproof',
                'stock' => 101,
                'price' => 850000.00,
                'weight' => 700,
                'length' => 40,
                'width' => 30,
                'height' => 5,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 29,
                'name' => 'Tas Coklat',
                'category' => 'Bags',
                'status' => 'active',
                'image' => 'images/products/htNA5swNtrpJT9UXmZrcibIZzJIGX10EF7Jhpwks.jpg',
                'description' => 'Tas bahu bahan kulit sintetis warna coklat',
                'stock' => 100,
                'price' => 250000.00,
                'weight' => 450,
                'length' => 35,
                'width' => 25,
                'height' => 10,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 30,
                'name' => 'Jas Hitam',
                'category' => 'Formal Wear',
                'status' => 'active',
                'image' => 'images/products/Q4c1RfIgfiIEZI0HTeQhgbie0mxiXwujuRhHs8zX.jpg',
                'description' => 'Jas formal pria warna hitam executive',
                'stock' => 100,
                'price' => 500000.00,
                'weight' => 800,
                'length' => 45,
                'width' => 35,
                'height' => 5,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 31,
                'name' => 'Sepatu Nike',
                'category' => 'Footwear',
                'status' => 'active',
                'image' => 'images/products/SZIXgjMhAMTxU34ajEJUNd2HSVcR8zrmNSxJ7poh.jpg',
                'description' => 'Sepatu sneakers Nike edisi khusus warna merah',
                'stock' => 1003,
                'price' => 950000.00,
                'weight' => 850,
                'length' => 32,
                'width' => 22,
                'height' => 14,
                'created_at' => '2026-09-08 19:12:57',
            ],
            [
                'id' => 32,
                'name' => 'Jaket Kulit Hitam',
                'category' => 'Outerwear',
                'status' => 'active',
                'image' => 'images/products/H1PpCwgLIpbSgkHhWGq8D7s9XIXGTbJCwxwPtPQS.jpg',
                'description' => 'Jaket kulit asli warna hitam pria',
                'stock' => 100,
                'price' => 500000.00,
                'weight' => 900,
                'length' => 42,
                'width' => 32,
                'height' => 6,
                'created_at' => '2026-09-08 19:12:57',
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['id' => $p['id']],
                [
                    'name' => $p['name'],
                    'category' => $p['category'],
                    'status' => $p['status'],
                    'description' => $p['description'],
                    'price' => $p['price'],
                    'stock' => $p['stock'],
                    'image' => $p['image'],
                    'weight' => $p['weight'] ?? 300,
                    'length' => $p['length'] ?? 40,
                    'width' => $p['width'] ?? 30,
                    'height' => $p['height'] ?? 20,
                    'created_at' => $p['created_at'],
                    'updated_at' => $p['created_at'],
                ]
            );
        }

        // ---- Product variants (diselaraskan dengan `algonation (1).sql`) ----
        $variants = [
            [
                'id' => 60,
                'product_id' => 31,
                'color' => 'Warna Hitam',
                'size' => '50 CM',
                'sku' => 'TS-BLK-50CM',
                'name' => 'Warna Hitam - 50 CM',
                'stock' => 10,
                'price' => 950000.00,
                'image' => 'product-variants/uLDC0edV2R78gsthTbZO1Z5zEH2QzZpeocuRbMtb.png',
                'created_at' => '2026-09-14 17:04:17',
                'updated_at' => '2026-09-14 17:21:01',
            ],
        ];

        foreach ($variants as $variantData) {
            ProductVariant::updateOrCreate(
                ['id' => $variantData['id']],
                [
                    'product_id' => $variantData['product_id'],
                    'color' => $variantData['color'],
                    'size' => $variantData['size'],
                    'sku' => $variantData['sku'],
                    'name' => $variantData['name'],
                    'stock' => $variantData['stock'],
                    'price' => $variantData['price'],
                    'image' => $variantData['image'],
                    'created_at' => $variantData['created_at'],
                    'updated_at' => $variantData['updated_at'],
                ]
            );
        }

        // ---- Featured products (diselaraskan dengan `algonation (1).sql`) ----
        $featuredProducts = [
            ['id' => 1, 'product_id' => 1, 'created_at' => '2026-09-08 19:12:37', 'updated_at' => '2026-09-08 19:12:37'],
            ['id' => 5, 'product_id' => 17, 'created_at' => '2026-09-08 19:12:57', 'updated_at' => '2026-09-08 19:12:57'],
            ['id' => 10, 'product_id' => 30, 'created_at' => '2026-09-08 19:21:37', 'updated_at' => '2026-09-08 19:21:37'],
            ['id' => 11, 'product_id' => 28, 'created_at' => '2026-09-08 19:21:37', 'updated_at' => '2026-09-08 19:21:37'],
            ['id' => 12, 'product_id' => 23, 'created_at' => '2026-09-08 19:21:37', 'updated_at' => '2026-09-08 19:21:37'],
            ['id' => 13, 'product_id' => 29, 'created_at' => '2026-09-08 19:21:37', 'updated_at' => '2026-09-08 19:21:37'],
            ['id' => 14, 'product_id' => 22, 'created_at' => '2026-09-08 19:21:37', 'updated_at' => '2026-09-08 19:21:37'],
            ['id' => 18, 'product_id' => 19, 'created_at' => '2026-09-14 08:06:00', 'updated_at' => '2026-09-14 08:06:00'],
        ];

        foreach ($featuredProducts as $featured) {
            FeaturedProduct::updateOrCreate(
                ['id' => $featured['id']],
                ['product_id' => $featured['product_id'], 'created_at' => $featured['created_at'], 'updated_at' => $featured['updated_at']]
            );
        }

        // ---- Flash sales (diselaraskan dengan `algonation (1).sql`) ----
        $flashSales = [
            ['id' => 1, 'product_id' => 31, 'normal_price' => 950000.00, 'sale_price' => 499999.99, 'discount_percentage' => 47.37, 'stock' => 5, 'start_at' => '2026-09-03 14:13:00', 'end_at' => '2026-10-15 17:15:00', 'status' => 'active', 'created_at' => '2026-09-08 19:21:37', 'updated_at' => '2026-09-13 20:28:26'],
            ['id' => 2, 'product_id' => 24, 'normal_price' => 1890000.00, 'sale_price' => 1000000.00, 'discount_percentage' => 47.09, 'stock' => 7, 'start_at' => '2026-09-03 14:13:00', 'end_at' => '2026-10-15 17:15:00', 'status' => 'active', 'created_at' => '2026-09-08 19:21:37', 'updated_at' => '2026-09-08 19:21:37'],
            ['id' => 3, 'product_id' => 19, 'normal_price' => 500000.00, 'sale_price' => 200000.00, 'discount_percentage' => 60.00, 'stock' => 5, 'start_at' => '2026-09-08 19:52:00', 'end_at' => '2026-10-15 19:52:00', 'status' => 'active', 'created_at' => '2026-09-08 19:21:37', 'updated_at' => '2026-09-14 08:24:01'],
        ];

        foreach ($flashSales as $flashSale) {
            FlashSale::updateOrCreate(
                ['id' => $flashSale['id']],
                [
                    'product_id' => $flashSale['product_id'],
                    'normal_price' => $flashSale['normal_price'],
                    'sale_price' => $flashSale['sale_price'],
                    'discount_percentage' => $flashSale['discount_percentage'],
                    'stock' => $flashSale['stock'],
                    'start_at' => $flashSale['start_at'],
                    'end_at' => $flashSale['end_at'],
                    'status' => $flashSale['status'],
                    'created_at' => $flashSale['created_at'],
                    'updated_at' => $flashSale['updated_at'],
                ]
            );
        }
    }
}
