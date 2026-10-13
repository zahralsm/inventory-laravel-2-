# 📦 Stockroom — Inventory Management (Laravel Edition)

Aplikasi manajemen inventaris barang berbasis **Laravel 12** yang diintegrasikan dan dimigrasikan dari project pendahulu PHP Native P4 (`d:\punya-zahra\inventory_management`) sesuai silabus **Pertemuan 5 (Web 5.pptx)**.

---

## 🏛️ Arsitektur MVC & Alur Data

Proyek ini menerapkan arsitektur MVC secara bersih sesuai standar Laravel:
```text
Request ➔ Route (web.php) ➔ Controller ➔ Model (Eloquent) ➔ Database (MySQL) ➔ Blade View / Response
```

- **Model & Eloquent**:
  - `User`: Akun pengguna dengan peran `admin` & `staff`.
  - `Category`: Kategori produk (`hasMany` ke Product).
  - `Supplier`: Data pemasok / vendor barang.
  - `Product`: Produk inventaris (`belongsTo` Category, `hasMany` StockMovement).
  - `StockMovement`: Riwayat pergerakan stok masuk (`IN`) dan keluar (`OUT`).

- **Controller & Resource Routing**:
  - `AuthController`: Mengatur alur login dengan hashing password dan proteksi session/logout.
  - `DashboardController`: Menghitung metrik total produk, kategori, supplier, valuasi aset, produk stok menipis, dan ringkasan transaksi.
  - `ProductController`: CRUD lengkap (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`), pagination, search, dan filter.
  - `CategoryController`: Manajemen data kategori dengan modal inline edit.
  - `SupplierController`: Manajemen kontak supplier dan vendor.
  - `StockMovementController`: Transaksi penambahan/pengurangan stok dengan `DB::transaction` dan penolakan stok keluar melebihi stok yang tersedia (*business rule validation*).

- **View & Blade Templates**:
  - Master layout di `resources/views/layouts/app.blade.php`.
  - Reusable alert di `resources/views/components/alert.blade.php`.
  - Proteksi form menggunakan directive `@csrf` dan validasi error `@error`.

---

## ⚙️ Persyaratan Sistem

- PHP 8.2+
- Composer 2+
- MySQL / MariaDB (XAMPP)

---

## 🚀 Panduan Menjalankan Proyek

### 1. Salin dan Setup Environment
Pastikan file `.env` sudah mengarah ke database MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Jalankan Migrasi dan Seeder
Jalankan perintah berikut untuk membuat struktur tabel dan mengimpor data awal (kategori, supplier, produk demo, dan riwayat mutasi stok):
```bash
php artisan migrate:fresh --seed
```

### 3. Jalankan Development Server
```bash
php artisan serve
```
Akses aplikasi melalui browser di:
👉 **[http://localhost:8000](http://localhost:8000)** (atau port default yang ditampilkan artisan).

---

## 🔑 Akun Bawaan untuk Pengujian

| Peran | Username | Password | Hak Akses |
|---|---|---|---|
| **Admin** | `admin` | `admin123` | Akses penuh CRUD produk, kategori, supplier, mutasi stok, & dashboard |
| **Staff** | `staff` | `staff123` | Melihat produk, mencatat mutasi stok (IN/OUT), dan dashboard |

---

## 🛡️ Business Rules yang Dipertahankan dari P4

1. **Stok Tidak Boleh Negatif**: Validasi input melarang harga dan stok bernilai minus.
2. **Stok Keluar Validated**: Stok keluar (OUT) ditolak jika melebihi stok fisik produk yang tersedia saat itu.
3. **Database Consistency**: Setiap mutasi stok tercatat ke tabel `stock_movements` dan diikat dengan transaksi database (`DB::transaction`).
4. **Role-Based Protection**: Fitur penghapusan & pengubahan kategori/supplier diproteksi khusus untuk Administrator melalui middleware `role:admin`.
