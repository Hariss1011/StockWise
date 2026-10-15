# StockWise — Inventory Management System

StockWise adalah aplikasi manajemen persediaan barang berbasis web yang dibangun menggunakan Laravel dan MySQL. Aplikasi ini dirancang untuk membantu pengelolaan data produk, pencatatan pergerakan stok, pemantauan persediaan, serta penyusunan laporan inventaris secara terstruktur.

Proyek ini dikembangkan sebagai portofolio untuk menunjukkan kemampuan dalam pengembangan aplikasi web, perancangan basis data, validasi data, dan pengolahan data menggunakan Excel.

## Fitur Utama

### Dashboard

- Ringkasan data produk dan kondisi persediaan.
- Informasi stok minimum untuk membantu pemantauan barang.

### Manajemen Data

- CRUD (Create, Read, Update, Delete) produk.
- Pengelolaan kategori dan supplier.
- Pengaturan SKU, harga beli, harga jual, stok awal, dan stok minimum.

### Transaksi Persediaan

- Pencatatan barang masuk.
- Pencatatan barang keluar.
- Penyesuaian stok (_stock adjustment_).
- Perhitungan stok berdasarkan stok awal dan transaksi.
- Peringatan produk dengan stok minimum.

### Laporan dan Pengolahan Data

- Laporan stok barang.
- Laporan transaksi persediaan.
- Laporan stok berdasarkan tanggal historis.
- Filter data laporan.
- Ekspor laporan ke CSV.
- Impor produk dari file Excel.
- Unduh template impor produk.
- Validasi data impor, termasuk SKU duplikat, kategori, supplier, dan format nilai.

### Autentikasi dan Pengelolaan Pengguna

- Login dan logout.
- Pembagian hak akses Admin dan Staff.
- Pengelolaan pengguna untuk Admin.
- Pencatatan aktivitas sistem (_activity logs_) untuk Admin.

## Teknologi yang Digunakan

- **Backend:** PHP, Laravel
- **Frontend:** Blade, Tailwind CSS
- **Database:** MySQL
- **Excel Import/Export:** Laravel Excel dan PhpSpreadsheet
- **Development Environment:** Laragon, Visual Studio Code
- **Version Control:** Git dan GitHub

## Persyaratan Sistem

Pastikan perangkat telah memiliki:

- PHP yang memenuhi persyaratan versi Laravel dan dependency proyek.
- Composer.
- MySQL atau MariaDB.
- Node.js dan npm untuk proses build aset frontend.
- Git.

Versi dependency yang tepat dapat diperiksa melalui `composer.json` dan `composer.lock`.

## Entity Relationship Diagram (ERD)

ERD StockWise menggambarkan hubungan antara pengguna, aktivitas sistem, kategori, supplier, produk, dan transaksi persediaan.

Diagram lengkap dapat dilihat pada [docs/erd.md](docs/erd.md).

## Instalasi dan Menjalankan Aplikasi

### 1. Clone repository

```bash
git clone https://github.com/Hariss1011/StockWise.git
cd StockWise
```

### 2. Instal dependency PHP

```bash
composer install
```

### 3. Siapkan konfigurasi environment

Salin `.env.example` menjadi `.env`.

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Kemudian buat application key:

```bash
php artisan key:generate
```

### 4. Konfigurasikan database

Buat database baru di MySQL, kemudian sesuaikan konfigurasi berikut di file `.env`:

```env
APP_NAME=StockWise
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stockwise
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nama database, username, dan password dengan konfigurasi MySQL lokal. Pastikan pengaturan timezone aplikasi juga sesuai dengan konfigurasi proyek.

### 5. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

Perintah ini menjalankan migration dan seeder yang tersedia untuk menyiapkan struktur basis data beserta data awal.

### 6. Instal dan build aset frontend

```bash
npm install
npm run build
```

### 7. Jalankan aplikasi

```bash
php artisan serve
```

Buka alamat berikut di browser:

http://127.0.0.1:8000

Ikuti konfigurasi autentikasi atau akun demo yang tersedia pada versi proyek yang digunakan.

## Struktur Data

Aplikasi menggunakan beberapa tabel utama untuk memisahkan data master, transaksi, dan aktivitas sistem.

| Tabel               | Fungsi                           |
| ------------------- | -------------------------------- |
| `users`             | Data pengguna dan hak akses      |
| `categories`        | Data kategori produk             |
| `suppliers`         | Data pemasok                     |
| `products`          | Data master produk dan stok awal |
| `stock_ins`         | Transaksi barang masuk           |
| `stock_outs`        | Transaksi barang keluar          |
| `stock_adjustments` | Penyesuaian stok                 |
| `activity_logs`     | Riwayat aktivitas sistem         |

Pemisahan data master dan transaksi membantu menjaga struktur data tetap terorganisasi serta mendukung perhitungan stok dan penyusunan laporan.

## Validasi dan Pengujian

Pengujian fitur impor produk mencakup beberapa skenario:

- SKU duplikat.
- Kategori tidak ditemukan.
- Supplier tidak ditemukan.
- Harga diisi dengan teks.
- Nama produk kosong.
- Data produk valid.
- Penolakan impor ketika terdapat data tidak valid.

Skenario tersebut telah diuji dan memberikan hasil sesuai yang diharapkan. Pengujian fitur lainnya dilakukan sesuai skenario yang tersedia pada tahap pengembangan.

## Data Demo

Data contoh yang digunakan untuk demonstrasi aplikasi ditujukan untuk keperluan pengujian dan portofolio, bukan data operasional perusahaan nyata.

## Pengembangan Selanjutnya

Pengembangan berikutnya dapat mencakup penyempurnaan dokumentasi teknis, diagram relasi basis data (ERD), dokumentasi pengujian, serta penyediaan demo aplikasi yang dapat diakses secara daring.

## Author

**Akhmad Haris**

GitHub: [Hariss1011](https://github.com/Hariss1011)

Repository: [StockWise](https://github.com/Hariss1011/StockWise)
