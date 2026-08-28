# TODO — Pindah ke SQLite & Perbaiki Login Admin

## Langkah
- [x] Analisis masalah: `.env` diset `DB_CONNECTION=mysql` & `DB_DATABASE=perabotan_rumahku` (duplikat) → aplikasi terhubung ke MySQL kosong
- [ ] Edit `.env`: `DB_CONNECTION=sqlite`, hapus duplikat DB_, set `DB_DATABASE` ke `database/database.sqlite`
- [ ] Bersihkan config cache (`php artisan config:clear` & `php artisan config:cache`)
- [ ] Verifikasi koneksi aktif = SQLite & data ada (via tinker)
- [ ] Verifikasi login admin berhasil
- [ ] Jalankan `php artisan migrate:status` pastikan semua migrasi aktif di SQLite
- [ ] Hapus file sementara `_check_sqlite_tmp.php`

