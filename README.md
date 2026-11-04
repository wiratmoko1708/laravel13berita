# Portal Berita Sekolah

Web berita (portal berita sekolah) berbasis **Laravel 13** + **MySQL/MariaDB** + **PHP 8.4**,
dibangun mengikuti panduan *Tutorial_Laravel13_Web_Berita.pdf* (versi 2.0).

## Fitur

- Registrasi & login (Laravel Breeze — stack Blade + Tailwind CSS)
- Tiga peran pengguna: **pembaca**, **penulis**, **admin** (middleware `role`)
- CRUD kategori berita (khusus admin, slug otomatis)
- CRUD berita dengan upload gambar, slug otomatis, status **draft** / **terbit**
- Halaman publik: beranda, pencarian, detail berita, daftar per kategori
- Dashboard statistik per peran
- Tanggal berbahasa Indonesia (`APP_LOCALE=id`)

## Kebutuhan

| Perangkat | Versi yang dipakai |
|---|---|
| PHP | 8.4.27 (Homebrew) |
| Laravel | 13.40.0 |
| MySQL | 9.7.1 (Homebrew) — MariaDB juga kompatibel |
| Node.js / npm | 26.0.0 / 11.x |
| Composer | 2.x |

## Struktur data

```
users      : id, name, email, role(pembaca|penulis|admin), password, ...
categories : id, nama, slug
posts      : id, user_id, category_id, judul, slug, ringkasan, isi,
             gambar, status(draft|terbit), published_at
```

## Cara menjalankan

```bash
# 1. Masuk ke folder proyek
cd /Users/wikanwiratmoko/Sites/berita

# 2. Siapkan database (sekali saja)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS db_berita CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Sesuaikan kredensial database di file .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 4. Migrasi + akun admin
php artisan migrate
php artisan db:seed --class=AdminSeeder

# 5. Symlink gambar + build aset (jika belum)
php artisan storage:link
npm install && npm run build

# 6. Jalankan
php artisan serve --host=127.0.0.1 --port=8000
```

Buka <http://127.0.0.1:8000>.

### Akun admin bawaan

| Email | Password | Peran |
|---|---|---|
| `admin@berita.test` | `admin12345` | admin |

> **Penting:** ganti password ini sebelum aplikasi dipakai orang lain.

## Route penting

| URL | Keterangan | Akses |
|---|---|---|
| `/` | Beranda + pencarian (`?q=`) | publik |
| `/berita/{slug}` | Detail berita | publik |
| `/kategori/{slug}` | Daftar berita per kategori | publik |
| `/dashboard` | Dashboard | login |
| `/admin/berita` | Kelola berita | admin, penulis |
| `/admin/kategori` | Kelola kategori | admin |
| `/admin/pengguna` | Kelola pengguna (tambah/ubah/hapus) | admin |

## Kelola Pengguna (`/admin/pengguna`)

Halaman ini adalah CRUD penuh untuk pengguna (khusus admin):

| Aksi | URL | Keterangan |
|---|---|---|
| Lihat daftar | `GET /admin/pengguna` | tabel nama, email, peran + paginasi 10 |
| Tambah | `GET/POST /admin/pengguna/create` | nama, email, peran, password + konfirmasi |
| Ubah | `GET/PUT /admin/pengguna/{user}/edit` | nama, email, peran, password (opsional) |
| Hapus | `DELETE /admin/pengguna/{user}` | dengan konfirmasi `confirm()` |

Pengamanan yang diterapkan:

- Admin **tidak bisa menurunkan peran akunnya sendiri** (agar tidak terkunci).
- Admin **tidak bisa menghapus akunnya sendiri** (tombol Hapus memang tidak ditampilkan).
- **Admin terakhir tidak bisa dihapus.**
- Email dinormalkan ke huruf kecil dan divalidasi unik (`Rule::unique(...)->ignore()` saat ubah).
- Password minimal 8 karakter + wajib dikonfirmasi; saat mengubah pengguna, kolom password
  boleh dikosongkan agar password lama tidak berubah.
- Password otomatis di-hash oleh cast `'password' => 'hashed'` pada model `User`.

## Catatan implementasi

1. **Route model binding berita.** `Route::resource('berita', ...)` membuat parameter
   `{beritum}` (bentuk tunggal otomatis), sedangkan controller memakai `Post $berita`.
   Karena itu route diberi `->parameters(['berita' => 'berita'])` pada `routes/web.php`
   agar binding tetap bekerja.
2. **Model User** memakai properti `$fillable` (bukan atribut `#[Fillable]` bawaan skeleton
   Laravel 13) agar sama dengan tutorial, dan ditambah method `isAdmin()`, `isPenulis()`,
   serta relasi `posts()`.
3. **Locale** diatur lewat `APP_LOCALE=id` di `.env` dan default `config/app.php`
   (`env('APP_LOCALE', 'id')`), sehingga `translatedFormat('d F Y')` menghasilkan
   nama bulan Indonesia (mis. `05 Oktober 2026`).
4. **Penyimpanan gambar** memakai disk `public` (`storage/app/public/berita`) dan diakses
   lewat `/storage/berita/...` setelah `php artisan storage:link`.
5. Pesan validasi masih berbahasa Inggris (belum ada file `lang/id`), sesuai tutorial.

## Pengujian

```bash
php artisan test        # 25 test, semuanya lulus
php artisan route:list --except-vendor
```

Checklist manual ada di Bab 7.1 panduan.