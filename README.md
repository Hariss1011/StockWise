# StockWise — Inventory Management System

**StockWise** adalah aplikasi manajemen persediaan barang berbasis web yang dikembangkan menggunakan Laravel dan MySQL. Aplikasi ini membantu pengguna mengelola data produk, mencatat pergerakan stok, memantau persediaan minimum, serta menghasilkan laporan inventaris berdasarkan periode tertentu.

Proyek ini dikembangkan sebagai portofolio untuk menunjukkan kemampuan dalam **pengembangan aplikasi web, perancangan basis data relasional, pengolahan data, validasi input, dan penyusunan laporan**.

## Preview Aplikasi

<!-- Sesuaikan nama file dengan screenshot yang tersedia di folder docs/screenshots -->

Tambahkan screenshot dashboard utama pada bagian ini agar pengunjung GitHub dapat langsung melihat tampilan aplikasi.

Contoh jika nama file screenshot adalah `dashboard.png`:

`![StockWise Dashboard](docs/screenshots/dashboard.png)`

Lihat folder [docs/screenshots](docs/screenshots/) untuk dokumentasi visual aplikasi.

## Fitur Utama

### 1. Dashboard

- Menampilkan ringkasan data produk dan kondisi persediaan.
- Memantau produk yang mencapai atau berada di bawah batas stok minimum.

### 2. Manajemen Data Master

- Mengelola data produk dengan fitur tambah, lihat, edit, dan hapus.
- Mengelola kategori dan supplier.
- Menyimpan informasi SKU, satuan, harga beli, harga jual, stok awal, dan stok minimum.
- Mengimpor data produk dari Excel menggunakan template yang disediakan.

### 3. Manajemen Transaksi Stok

- **Stock In:** mencatat barang masuk.
- **Stock Out:** mencatat barang keluar beserta informasi penerima.
- **Stock Adjustment:** mencatat penyesuaian stok dan alasannya.
- Menghitung stok berdasarkan stok awal dan riwayat transaksi.
- Mencegah transaksi barang keluar yang menyebabkan stok menjadi negatif.

### 4. Laporan dan Pengolahan Data

- Menampilkan laporan stok barang dan transaksi persediaan.
- Menyajikan kondisi stok berdasarkan tanggal historis yang dipilih.
- Menyediakan filter untuk memudahkan pencarian dan analisis data.
- Mengekspor laporan stok, transaksi, dan ringkasan aktivitas ke CSV.
- Mengimpor produk dari Excel dengan validasi data, termasuk pemeriksaan SKU duplikat, kategori, supplier, dan format nilai.

### 5. Autentikasi dan Pengelolaan Pengguna

- Menyediakan fitur login dan logout.
- Menerapkan pembagian hak akses Admin dan Staff.
- Menyediakan pengelolaan pengguna untuk Admin.
- Mencatat aktivitas sistem melalui fitur _activity logs_ yang dapat diakses Admin.

## Teknologi yang Digunakan

| Teknologi          | Kegunaan                             |
| ------------------ | ------------------------------------ |
| PHP                | Bahasa pemrograman backend           |
| Laravel            | Framework aplikasi web               |
| Blade              | Template engine untuk antarmuka      |
| Tailwind CSS       | Styling dan desain antarmuka         |
| MySQL              | Sistem manajemen basis data          |
| Laravel Excel      | Pemrosesan file Excel                |
| PhpSpreadsheet     | Pengolahan spreadsheet               |
| Git & GitHub       | Version control dan pengelolaan kode |
| Laragon            | Lingkungan pengembangan lokal        |
| Visual Studio Code | Editor kode                          |

## Arsitektur dan Struktur Data

StockWise memisahkan data master, transaksi persediaan, dan aktivitas sistem ke dalam tabel-tabel yang berbeda. Pendekatan ini membantu menjaga struktur data tetap terorganisasi serta mempermudah pengembangan fitur laporan dan perhitungan stok.

| Tabel               | Fungsi                           |
| ------------------- | -------------------------------- |
| `users`             | Data pengguna dan hak akses      |
| `categories`        | Data kategori produk             |
| `suppliers`         | Data pemasok                     |
| `products`          | Data master produk dan stok awal |
| `stock_ins`         | Riwayat transaksi barang masuk   |
| `stock_outs`        | Riwayat transaksi barang keluar  |
| `stock_adjustments` | Riwayat penyesuaian stok         |
| `activity_logs`     | Riwayat aktivitas sistem         |

Dokumentasi hubungan antartabel dapat dilihat pada [Entity Relationship Diagram (ERD)](docs/erd.md).

## Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat telah memiliki:

- PHP dengan versi yang sesuai dengan dependency proyek.
- Composer.
- MySQL atau MariaDB.
- Node.js dan npm.
- Git.

Versi dependency yang digunakan dapat diperiksa melalui `composer.json`, `composer.lock`, dan `package.json`.

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

### 3. Konfigurasikan environment

Salin file `.env.example` menjadi `.env`.

Untuk Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Kemudian buat application key:

```bash
php artisan key:generate
```

### 4. Siapkan database

Buat database baru di MySQL, misalnya dengan nama `stockwise`. Sesuaikan konfigurasi database pada file `.env` dengan lingkungan lokal yang digunakan.

Contoh konfigurasi:

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

Sesuaikan nama database, username, password, dan konfigurasi lainnya jika berbeda dari contoh di atas.

### 5. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

Perintah tersebut menjalankan migration dan seeder yang tersedia untuk menyiapkan struktur database serta data awal aplikasi.

**Catatan:** Jalankan seeder hanya pada database pengembangan atau database kosong yang memang disiapkan untuk demonstrasi. Periksa konfigurasi seeder terlebih dahulu sebelum menggunakannya pada database yang berisi data penting.

### 6. Instal dan build aset frontend

```bash
npm install
npm run build
```

### 7. Jalankan aplikasi

```bash
php artisan serve
```

Buka aplikasi melalui browser:

http://127.0.0.1:8000

Gunakan akun yang telah disiapkan pada lingkungan pengembangan. Jika kredensial demo belum didokumentasikan, periksa konfigurasi seeder atau buat akun melalui mekanisme yang tersedia di aplikasi.

## Validasi dan Pengujian

Validasi dan pengujian dilakukan untuk memastikan data yang diproses sesuai dengan aturan aplikasi.

Skenario pengujian impor produk meliputi:

- SKU duplikat.
- Kategori yang tidak ditemukan.
- Supplier yang tidak ditemukan.
- Harga dengan format tidak valid.
- Nama produk kosong.
- Data produk yang valid.
- Penolakan impor ketika terdapat data tidak valid.

Skenario tersebut telah diuji selama pengembangan. Pengujian fitur lain dilakukan sesuai skenario yang diterapkan pada masing-masing modul.

## Data Demonstrasi

Data contoh digunakan untuk menguji fitur dan mendemonstrasikan alur kerja aplikasi. Data tersebut ditujukan untuk kebutuhan pengembangan dan portofolio, bukan untuk merepresentasikan transaksi perusahaan nyata.

## Pengembangan Selanjutnya

Beberapa peluang pengembangan StockWise meliputi:

- Penyempurnaan dokumentasi hasil pengujian.
- Penambahan visualisasi tren pergerakan stok.
- Pengembangan analisis produk dengan perputaran stok tinggi atau rendah.
- Penyediaan demo aplikasi secara daring.

## Author

**Akhmad Haris**

- GitHub: [@Hariss1011](https://github.com/Hariss1011)
- Repository: [StockWise — Inventory Management System](https://github.com/Hariss1011/StockWise)
