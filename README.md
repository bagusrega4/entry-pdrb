# Si-PRABU

Sistem Informasi Pengambilan Keputusan untuk Analisis Pertumbuhan Ekonomi Kabupaten Kediri: aplikasi web untuk pengolahan data PDRB, pengarsipan dokumen pendukung, pemodelan Input–Output (IO), dan dashboard analitik di BPS Kabupaten Kediri.

> Status aplikasi: prototipe penelitian dan artefak skripsi, bukan layanan resmi BPS.

## 1. Identitas Artefak

| Komponen | Keterangan |
|---|---|
| Nama artefak | Si-PRABU |
| Bentuk artefak | Aplikasi web |
| Pengembang / peneliti | Agape Bagus Rega Anggara |
| NIM | 222212455 |
| Kelas | 4SI1 |
| Program studi | D-IV Komputasi Statistik |
| Perguruan tinggi | Politeknik Statistika STIS |
| Dosen pembimbing | Ibnu Santoso, S.S.T., M.T. |
| Tahun akademik | 2026/2027 |
| Judul skripsi | Pembangunan Si-PRABU: Platform Pengolahan dan Analisis PDRB di BPS Kabupaten Kediri |
| Website | https://siprabu.radata.id |

## 2. Struktur Repository

```text
app/
├── Console/
│   ├── Commands/
│   │   └── TruncateTables.php
│   └── Kernel.php
├── Enums/
├── Exceptions/
├── Exports/
│   ├── Sheets/
│   ├── IhpExport.php
│   ├── PriceProductionExport.php
│   ├── RasioExport.php
│   └── WipCbrExport.php
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── DashboardController.php
│   │   ├── DashboardExportController.php
│   │   ├── DocumentController.php
│   │   ├── DownloadController.php
│   │   ├── FinalisasiPdrbController.php
│   │   ├── IOController.php
│   │   ├── IhpController.php
│   │   ├── ManageUserController.php
│   │   ├── PdrbController.php
│   │   ├── PriceProductionController.php
│   │   ├── ProfileController.php
│   │   ├── RasioController.php
│   │   ├── SimulasiController.php
│   │   └── WipCbrController.php
│   ├── Middleware/
│   │   └── RoleMiddleware.php
│   ├── Requests/
│   └── Kernel.php
├── Imports/
├── Models/
├── Providers/
└── View/Components/

bootstrap/
config/
database/
├── factories/
├── migrations/
└── seeders/
    └── data/

public/
├── assets/
└── css/

resources/
├── css/
├── js/
└── views/
    ├── auth/
    ├── components/
    ├── dashboard/
    ├── documents/
    ├── download/
    ├── errors/
    ├── finalisasi/
    ├── ihp/
    ├── io/
    ├── layouts/
    ├── manage/user/
    ├── pdrb/
    ├── prices_productions/
    ├── profile/
    ├── rasio/
    ├── simulasi/
    ├── vendor/pagination/
    ├── wip-cbr/
    ├── _navbar.blade.php
    └── _sidebar.blade.php

routes/
├── api.php
├── auth.php
├── channels.php
├── console.php
└── web.php

storage/
stubs/
.env.example
artisan
composer.json
package.json
phpunit.xml
tailwind.config.js
vite.config.js
```

### 2.1. Backend (`app/`)

| Folder / berkas | Deskripsi singkat |
|---|---|
| `Console/Commands/TruncateTables.php` | Perintah `db:truncate` untuk mengosongkan seluruh tabel tanpa menghapus strukturnya. |
| `Enums/` | Enumerasi aplikasi (`Role`, `Status`). |
| `Exports/` | Kelas ekspor Excel untuk modul IHP, Harga-Produksi, Rasio, dan WIP/CBR. |
| `Imports/` | Kelas impor Excel untuk keempat modul data pendukung. |
| `Http/Controllers/Auth/` | Controller autentikasi (login, registrasi, lupa dan reset kata sandi, verifikasi surel). |
| `Http/Controllers/PriceProductionController.php`, `RasioController.php`, `IhpController.php`, `WipCbrController.php` | Input data pendukung PDRB: Harga dan Produksi, Rasio, IHP, WIP/CBR. |
| `Http/Controllers/PdrbController.php` | Pembentukan (perhitungan) PDRB. |
| `Http/Controllers/FinalisasiPdrbController.php` | Finalisasi PDRB dan riwayat versi. |
| `Http/Controllers/IOController.php` | Pengelolaan tabel IO, Matriks A, dan Matriks Leontief. |
| `Http/Controllers/SimulasiController.php` | Simulasi IO, skenario, riwayat, dan ekspor hasil. |
| `Http/Controllers/DashboardController.php`, `DashboardExportController.php` | Dashboard analitik dan ekspor PDF dashboard. |
| `Http/Controllers/DocumentController.php` | Dokumen pendukung (unggah, lihat, unduh, ubah, hapus). |
| `Http/Controllers/DownloadController.php` | Laporan PDF dan CSV. |
| `Http/Controllers/ManageUserController.php` | Kelola pengguna (peran, tim, ubah, hapus). |
| `Http/Controllers/ProfileController.php` | Profil, foto profil, dan ubah kata sandi. |
| `Http/Middleware/RoleMiddleware.php` | Pembatasan akses berdasarkan `id_role`. |
| `Models/` | Model Eloquent: `User`, `Role`, `Tim`, `Pegawai`, `Commodity`, `Indicator`, `Triwulan`, model satuan (`UnitHarga`, `UnitProduksi`, `UnitLuas`, `UnitPerawatan`), model data pendukung (`CommodityPriceProduction`, `CommodityRasio`, `CommodityIhp`, `CommodityWipCbr`), `Document`, model IO (`IoDataset`, `IoTransaction`, `IoFinalDemand`, `IoOutput`), `PdrbResult`, `PdrbFinal`, `Economicgroup`, `DownloadColumnConfig`. |

### 2.2. Database (`database/`)

| Folder / berkas | Deskripsi singkat |
|---|---|
| `migrations/` | Skema tabel pengguna, komoditas, data pendukung, dokumen, tabel IO, hasil dan finalisasi PDRB, grup ekonomi, riwayat simulasi, dan konfigurasi kolom laporan. |
| `seeders/` | Seeder data awal; `DatabaseSeeder.php` memanggil seeder utama. |
| `seeders/data/` | Sumber data awal (`commodities`, `indicators`, `pegawai`, `tim`, `triwulan`, satuan, `users`, dan `role.csv`). |

### 2.3. Frontend (`resources/` dan `public/`)

| Folder / berkas | Deskripsi singkat |
|---|---|
| `resources/views/layouts/` | Tata letak utama aplikasi. |
| `resources/views/_navbar.blade.php`, `_sidebar.blade.php` | Navigasi atas dan sidebar. |
| `resources/views/dashboard/` | Dashboard analitik dan templat ekspor PDF. |
| `resources/views/prices_productions/`, `rasio/`, `ihp/`, `wip-cbr/` | Halaman input data pendukung PDRB. |
| `resources/views/documents/` | Halaman dokumen pendukung. |
| `resources/views/pdrb/`, `finalisasi/` | Halaman pembentukan dan finalisasi PDRB. |
| `resources/views/io/` | Halaman tabel IO (daftar, buat, input, Matriks A, Leontief). |
| `resources/views/simulasi/` | Halaman simulasi IO, skenario, riwayat, dan templat ekspor PDF. |
| `resources/views/download/` | Halaman laporan dan templat PDF. |
| `resources/views/manage/user/`, `profile/`, `auth/` | Kelola pengguna, profil, dan autentikasi. |
| `resources/css/`, `resources/js/` | Aset yang dibangun dengan Vite. |
| `public/assets/` | Aset statis (CSS, JavaScript, gambar, font, pustaka vendor). |

### 2.4. Rute dan konfigurasi

| Folder / berkas | Deskripsi singkat |
|---|---|
| `routes/web.php` | Rute utama dan pembatasan peran (`role:1,2` dan `role:2`). |
| `routes/auth.php` | Rute autentikasi. |
| `config/` | Konfigurasi Laravel, termasuk `excel.php` dan `dompdf.php`. |
| `.env.example` | Contoh konfigurasi lingkungan. |
| `composer.json`, `package.json` | Dependensi PHP dan JavaScript. |
| `vite.config.js`, `tailwind.config.js` | Konfigurasi build frontend. |
