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

## Menjalankan di Windows 11 (clone dari GitHub)

Repositori: <https://github.com/wiratmoko1708/laravel13berita.git> (branch `main`).

### 1. Pasang perkakas (sekali saja)

| Perkakas | Sumber | Catatan |
|---|---|---|
| Git for Windows | <https://git-scm.com/download/win> | sudah termasuk *Git Bash* & *Credential Manager* |
| PHP 8.3+ | <https://windows.php.net/download/> atau Laragon/XAMPP | aktifkan ekstensi `fileinfo`, `mbstring`, `openssl`, `pdo_mysql`, `zip` |
| Composer | <https://getcomposer.org/download/> | pilih *Composer-Setup.exe* |
| Node.js LTS | <https://nodejs.org/> | butuh Node 20.19+ / 22.12+ untuk Vite 8 |
| MySQL/MariaDB | Laragon/XAMPP atau <https://dev.mysql.com/downloads/installer/> | MySQL 8+ atau MariaDB 10.6+ |

Cek semuanya di **PowerShell** atau **Git Bash**:

```powershell
git --version
php -v
composer -V
node -v
npm -v
```

Jika muncul *"php is not recognized"*, tambahkan folder PHP ke **Environment Variables → Path**,
lalu tutup dan buka ulang terminal.

### 2. Clone repositori

Buka **PowerShell**, lalu pilih folder kerja (contoh `C:\laragon\www` atau `D:\projek`):

```powershell
cd C:\laragon\www
git clone https://github.com/wiratmoko1708/laravel13berita.git
cd laravel13berita
```

Saat pertama kali `clone`/`push`, jendela *Git Credential Manager* akan terbuka — login ke akun
GitHub `wiratmoko1708` lewat browser. Kredensial disimpan otomatis, jadi tidak perlu mengetik
password lagi.

Alternatif tanpa terminal: buka halaman repo → tombol **Code** → **Download ZIP**, ekstrak,
lalu lanjut ke langkah 3 (tanpa riwayat git).

### 3. Siapkan file `.env`

PowerShell:

```powershell
Copy-Item .env.example .env
```

Command Prompt (cmd):

```bat
copy .env.example .env
```

Buka `.env` (Notepad/VS Code) dan sesuaikan kredensial database:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_berita
DB_USERNAME=root
DB_PASSWORD=
```

> Laragon/XAMPP umumnya memakai `root` tanpa password. Jika MySQL Anda berpassword, isi `DB_PASSWORD`.

### 4. Buat database

Lewat phpMyAdmin (<http://localhost/phpmyadmin>) buat database `db_berita` dengan collation
`utf8mb4_unicode_ci`, **atau** lewat terminal:

```powershell
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS db_berita CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Instal dependensi, migrasi, dan build aset

```powershell
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed --class=AdminSeeder
php artisan storage:link
npm install
npm run build
```

Catatan Windows:

- `php artisan storage:link` membuat *symlink*. Jika gagal, aktifkan **Settings → System →
  For developers → Developer Mode**, atau jalankan PowerShell **as Administrator**.
- Jika `php artisan storage:link` tetap gagal, gambar berita tidak akan tampil; jalankan
  perintahnya sekali saja dengan hak admin.
- `npm install` memakai `.npmrc` proyek (`ignore-scripts=true`), jadi aman dijalankan apa adanya.

### 6. Jalankan aplikasi

```powershell
php artisan serve
```

Buka <http://127.0.0.1:8000>. Login admin: `admin@berita.test` / `admin12345`.

Untuk mode pengembangan (server + Vite hot reload sekaligus):

```powershell
composer run dev
```

### 7. Perintah Git harian di Windows

```powershell
git status                  # lihat perubahan
git pull origin main        # ambil update terbaru
git add .
git commit -m "pesan perubahan"
git push origin main        # kirim ke GitHub
```

Jika `git push` ditolak karena `main` sudah berubah:

```powershell
git pull --rebase origin main
git push origin main
```

### 8. Masalah yang sering muncul di Windows

| Gejala | Penyebab & solusi |
|---|---|
| `php is not recognized` | PHP belum masuk `PATH` → tambahkan di Environment Variables, buka terminal baru |
| `could not find driver` (pdo_mysql) | ekstensi `pdo_mysql` belum aktif di `php.ini` |
| `Vite manifest not found` | jalankan `npm run build` (atau `npm run dev` saat mengembangkan) |
| Port 8000 dipakai | `php artisan serve --port=8080` |
| `npm install` lambat/gagal | hapus `node_modules` + `package-lock.json`, ulangi `npm install` |
| Warning `LF will be replaced by CRLF` | normal; `.gitattributes` proyek memaksa LF di repo, abaikan saja |
| Gambar berita tidak muncul | `php artisan storage:link` belum dijalankan |

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