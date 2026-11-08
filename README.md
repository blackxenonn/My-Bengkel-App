# Bengkel Management

Aplikasi manajemen bengkel berbasis web yang dikembangkan menggunakan Laravel dan MySQL. Aplikasi ini dirancang untuk membantu pengelolaan operasional bengkel, mulai dari manajemen produk, persediaan barang, transaksi penjualan, hingga laporan.

## Tentang Proyek

**Bengkel Management** merupakan sistem informasi berbasis web yang dibuat untuk mempermudah pengelolaan aktivitas operasional bengkel dalam satu aplikasi terintegrasi.

Aplikasi ini menyediakan sistem hak akses berdasarkan peran pengguna sehingga setiap pengguna dapat mengakses fitur sesuai tanggung jawabnya.

## Fitur Utama

### 1. Dashboard
- Ringkasan penjualan harian.
- Jumlah transaksi.
- Jumlah produk.
- Informasi stok menipis.
- Grafik penjualan.
- Produk terlaris.
- Aktivitas pergerakan stok terbaru.

### 2. Manajemen Produk
- Menampilkan daftar produk.
- Menambahkan produk baru.
- Mengedit informasi produk.
- Menghapus produk.
- Pengelompokan berdasarkan kategori.
- Informasi harga modal dan harga jual.
- Informasi stok dan stok minimum.

### 3. Manajemen Kategori
- Menampilkan kategori produk.
- Menambahkan kategori.
- Mengedit kategori.
- Menghapus kategori.
- Mengatur status kategori.

### 4. POS / Kasir
- Menampilkan produk untuk transaksi.
- Pencarian produk berdasarkan nama atau SKU.
- Menambahkan produk ke keranjang.
- Mengatur jumlah barang.
- Menghitung total pembayaran.
- Validasi jumlah pembayaran.
- Menghitung uang kembalian.
- Menyimpan transaksi penjualan.

### 5. Riwayat Transaksi
- Menampilkan daftar transaksi.
- Melihat detail transaksi.
- Menampilkan informasi kasir.
- Melihat rincian barang yang dibeli.
- Mencetak struk transaksi.
- Mengajukan pembatalan transaksi.

### 6. Manajemen Pembatalan Transaksi
- Mengajukan pembatalan transaksi.
- Meninjau pengajuan pembatalan.
- Menyetujui atau menolak pengajuan.
- Mencatat alasan pembatalan.
- Mengembalikan stok secara otomatis ketika transaksi dibatalkan.

### 7. Manajemen Inventaris
- Mencatat barang masuk.
- Mencatat barang keluar.
- Melakukan penyesuaian stok.
- Mencatat alasan perubahan stok.
- Melihat riwayat pergerakan stok.
- Menyimpan informasi stok sebelum dan sesudah perubahan.

### 8. Manajemen Karyawan
- Menambahkan akun karyawan.
- Mengedit informasi karyawan.
- Menghapus akun karyawan.
- Mengatur peran pengguna.

### 9. Laporan
- Menampilkan ringkasan penjualan.
- Menampilkan jumlah transaksi.
- Menampilkan produk terjual.
- Menampilkan produk terlaris.
- Filter laporan berdasarkan periode tanggal.

## Hak Akses Pengguna

Aplikasi memiliki tiga jenis peran pengguna:

| Role | Hak Akses |
|---|---|
| Owner | Mengakses seluruh fitur aplikasi dan mengelola karyawan. |
| Kasir | Mengakses POS, riwayat transaksi, dan pengajuan pembatalan. |
| Gudang | Mengakses inventaris dan riwayat pergerakan stok. |

Owner memiliki akses penuh terhadap seluruh fitur aplikasi.

## Teknologi yang Digunakan

- **Backend:** Laravel 13
- **Programming Language:** PHP 8.3
- **Database:** MySQL / MariaDB
- **Frontend:** HTML, CSS, JavaScript
- **CSS Framework:** Bootstrap 5.3
- **Template Engine:** Laravel Blade
- **Development Environment:** Laragon
- **Version Control:** Git & GitHub

## Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat telah memiliki:

- PHP 8.3 atau versi yang sesuai dengan kebutuhan proyek.
- Composer.
- Node.js dan NPM.
- MySQL atau MariaDB.
- Git.
- Laragon atau lingkungan server lokal lainnya.

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/muhammadnafis23/my-bengkel-app.git bengkel-management
```

Masuk ke direktori proyek:

```bash
cd bengkel-management
```

### 2. Pilih Branch Pengembangan

Jika ingin menggunakan versi pengembangan:

```bash
git checkout development-branch
```

### 3. Install Dependencies

Install dependensi PHP:

```bash
composer install
```

Install dependensi frontend:

```bash
npm install
```

### 4. Konfigurasi Environment

Buat file `.env` berdasarkan file contoh:

```bash
copy .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Buat database MySQL dengan nama:

```text
bengkel_management
```

Kemudian sesuaikan konfigurasi database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bengkel_management
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password dengan konfigurasi MySQL lokal.

### 6. Menyiapkan Database

Ada dua cara untuk menyiapkan database.

**Opsi A — Menggunakan database hasil backup**

Import file SQL backup database melalui HeidiSQL agar mendapatkan struktur tabel dan data yang sudah disiapkan.

**Opsi B — Membuat database dari migration**

Jika menggunakan database kosong, jalankan:

```bash
php artisan migrate
```

Selanjutnya, siapkan role, akun awal, kategori, dan data produk melalui seeder atau aplikasi sesuai kebutuhan.

Catatan: Migration tidak otomatis mengisi seluruh data demo. Periksa seeder proyek sebelum menjalankan `php artisan db:seed`.

### 7. Build Frontend

```bash
npm run build
```

### 8. Jalankan Aplikasi

```bash
php artisan optimize:clear
php artisan storage:link
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://127.0.0.1:8000
```

## Struktur Proyek

Beberapa direktori utama Laravel yang digunakan:

```text
bengkel-management/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   └── Models/
├── bootstrap/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── products/
│       ├── categories/
│       ├── employees/
│       ├── pos/
│       ├── transactions/
│       ├── reports/
│       └── stock-movements/
├── routes/
│   └── web.php
├── .env.example
└── composer.json
```

## Keamanan

Beberapa mekanisme keamanan yang diterapkan:

- Autentikasi pengguna.
- Pembatasan akses berdasarkan role.
- Password disimpan menggunakan hashing.
- Validasi input pada proses penyimpanan.
- CSRF protection.
- Validasi stok saat transaksi.
- Pencatatan aktivitas perubahan stok.
- Penggunaan database transaction untuk menjaga konsistensi data.

## Pengembangan

Proyek ini masih dapat dikembangkan lebih lanjut, baik dari sisi antarmuka, pengelolaan transaksi, maupun peningkatan fungsionalitas sistem.

## Status Proyek

**Development**

Proyek dikembangkan secara bertahap dan masih dalam proses penyempurnaan.

## Kontributor

Dikembangkan sebagai proyek pengembangan aplikasi manajemen bengkel secara kolaboratif.

## Lisensi

Lisensi proyek belum ditentukan.
