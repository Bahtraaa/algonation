# 👕 ALGO NATION — E-Commerce

ALGO NATION is a modern **e-commerce** store for selling clothing and fashion accessories, built with **Laravel 12**, **SQLite**, **Tailwind CSS v4**, **Alpine.js**, and **Chart.js**.

## ✨ Fitur Utama

### Storefront Publik
- Landing page modern dengan hero section & animasi
- Katalog produk dengan pencarian instan & filter kategori
- Detail produk dengan pemilih varian (warna/ukuran)
- Keranjang belanja interaktif (drawer) dengan hitung subtotal otomatis
- Checkout dengan alamat pengiriman, estimasi ongkir, dan pembayaran **COD**
- Struk otomatis yang bisa di-print
- Fitur **Batalkan Pesanan** (hanya dalam 2 hari pertama / estimasi tiba > 1 hari)
- Profil pengguna & riwayat pesanan

### Panel Admin (RBAC)
- Dashboard dengan grafik pendapatan (Chart.js) & statistik
- Manajemen produk lengkap (CRUD + gambar + stok + varian)
- Laporan stok dengan peringatan stok menipis
- Laporan penjualan dengan filter tanggal & export **CSV/PDF**
- Manajemen pengguna (ubah role & status akun)
- Tema **Dark/Light mode**, desain glassmorphism, responsif (mobile-first)

## 🚀 Instalasi

```bash
# 1. Install dependency PHP
composer install

# 2. Buat file .env (jika belum ada)
copy .env.example .env

# 3. Generate app key
php artisan key:generate

# 4. Migrasi + seed data contoh
php artisan migrate:fresh --seed

# 5. Buat symlink storage (untuk upload gambar)
php artisan storage:link

# 6. Install & build asset frontend
npm install
npm run build

# 7. Jalankan server
php artisan serve
```

Buka **http://localhost:8000**.

## 🔑 Akun Demo

| Role     | Email                          | Password   |
|----------|--------------------------------|------------|
| Admin    | `admin@algonation.com`   | `password` |
| Customer | `customer@algonation.com`| `password` |

Panel admin: **http://localhost:8000/admin**

## 📦 Database (SQLite)

Aplikasi ini menggunakan database SQLite di:

```
C:\Users\HYPE AMD\OneDrive\Desktop\Herd\dfff\database\database.sqlite
```

### Tabel Utama

| Tabel                | Keterangan                                   |
|----------------------|----------------------------------------------|
| `users`              | Akun pengguna (admin & customer)             |
| `products`           | Produk (nama, kategori, gambar, stok, harga) |
| `product_variants`   | Varian produk (ukuran/warna, stok, harga)    |
| `transactions`       | Transaksi / pesanan (COD)                    |
| `transaction_details`| Detail item dalam transaksi                  |
| `migrations`         | Riwayat migrasi                              |

## 🗄️ Menghubungkan ke TablePlus

### Langkah 1 — Buka TablePlus

1. Klik tombol **"Create a new connection"** di pojok kiri bawah / tengah.
2. Pilih **SQLite** dari daftar database yang tersedia.

### Langkah 2 — Isi Koneksi

| Field        | Isi                                                                 |
|--------------|---------------------------------------------------------------------|
| **Database** | `C:\Users\HYPE AMD\OneDrive\Desktop\Herd\dfff\database\database.sqlite` |

> 💡 Atau klik tombol **"Choose" / folder icon** lalu navigasi ke:
> `Desktop/Herd/dfff/database/` dan pilih file `database.sqlite`.

### Langkah 3 — Nama Koneksi (Opsional)

Beri nama koneksi, misalnya **`PERABOTAN RUMAHKU`** agar mudah dikenali.

### Langkah 4 — Konek

1. Klik **Connect**.
2. TablePlus akan langsung menampilkan seluruh tabel & data.

### ⚠️ Catatan Penting

- **Jangan mengubah** data secara manual dari TablePlus saat aplikasi sedang berjalan, kecuali Anda paham konsekuensinya — perubahan langsung bisa menyebabkan inkonsistensi (misalnya stok berkurang saat checkout).
- File database **tidak perlu password** (SQLite murni).
- Jika ingin **reset data**, jalankan:
  ```bash
  php artisan migrate:fresh --seed
  ```
  Setelah itu, di TablePlus klik **Refresh** (atau tekan `⌘R` / `Ctrl+R`) untuk memuat ulang data.
- Pastikan server Laravel **tidak sedang menulis** ke database secara bersamaan saat Anda melakukan perubahan besar di TablePlus (untuk menghindari file lock).

## 🛠️ Teknologi

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite
- **Frontend**: Tailwind CSS v4, Alpine.js, Chart.js
- **Asset**: Vite

## 📄 Lisensi

Aplikasi ini open-source dan bebas digunakan untuk pembelajaran & pengembangan.

# algo_nation
