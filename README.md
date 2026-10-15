# Si-PRABU

Sistem Informasi Pengambilan Keputusan untuk Analisis Pertumbuhan Ekonomi Kabupaten Kediri: platform web untuk pengolahan data PDRB, pengarsipan dokumen pendukung, pemodelan Input–Output (IO), dan dashboard analitik di BPS Kabupaten Kediri.

## 1. Pengenalan

Si-PRABU dikembangkan sebagai artefak skripsi untuk mendukung tim Neraca Wilayah dan Analisis Statistik (Nerwilis) BPS Kabupaten Kediri. Sebelumnya, pengolahan PDRB dilakukan dengan lembar kerja Excel yang tersebar di banyak berkas, dokumen sumber tersimpan terpisah dari data hasil olahan, dan fenomena sektoral belum terdokumentasi secara sistematis. Si-PRABU menyatukan proses tersebut dalam satu platform dengan hak akses berbasis peran.

Fitur utama:

- Manajemen pengguna dan hak akses (Admin dan Operator).
- Data pendukung PDRB: empat modul input (Harga dan Produksi, Rasio, IHP, WIP/CBR) dengan impor Excel.
- Dokumen pendukung: unggah, pencarian, dan penelusuran arsip.
- Pembentukan PDRB (ADHB, ADHK, biaya antara, kontribusi sektor, pertumbuhan QoQ dan YoY) dan finalisasi PDRB dengan riwayat versi.
- Tabel IO: pengelolaan dataset, Matriks A, dan Matriks Leontief.
- Simulasi IO: simulasi stimulus sektoral, penyimpanan riwayat, dan komparasi skenario.
- Dashboard analitik interaktif dengan filter tahun dan triwulan.
- Laporan: ekspor PDF dan CSV.

> Status aplikasi: prototipe penelitian dan artefak skripsi, bukan layanan resmi BPS. Cakupan sistem adalah PDRB menurut lapangan usaha dengan pendekatan produksi. Pendekatan pengeluaran dan pendapatan, aplikasi mobile, serta integrasi dengan sistem BPS tingkat provinsi atau pusat tidak termasuk cakupan.

## 2. Identitas Artefak

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

Penjelasan metode, rancangan sistem, dan hasil evaluasi secara lengkap mengacu pada naskah skripsi.

## 3. Peran Pengguna

| Peran | `id_role` | Akses |
|---|---|---|
| Operator | 1 | Dashboard analitik dan Simulasi IO. |
| Admin | 2 | Seluruh akses Operator, ditambah kelola pengguna, data pendukung PDRB, dokumen pendukung, pembentukan dan finalisasi PDRB, kelola tabel IO, serta laporan. |

Pembatasan akses diterapkan melalui middleware `role` pada `routes/web.php`. Halaman di luar hak akses menampilkan galat 403.

## 4. Fitur Utama

### 4.1. Data Pendukung PDRB

Empat modul input data pendukung, masing-masing dengan pohon komoditas, pilihan indikator dan satuan, input per triwulan, serta impor dan templat Excel:

| Modul | Rute |
|---|---|
| Harga dan Produksi | `/prices-productions` |
| Rasio | `/rasio` |
| Indeks Harga Produsen (IHP) | `/ihp` |
| WIP/CBR | `/wip-cbr` |

### 4.2. Dokumen Pendukung

Admin mengunggah dokumen (nama, tahun, triwulan, modul, komoditas, jenis, keterangan), mencari dan memfilter arsip, melihat berkas, mengunduh, mengubah metadata, dan menghapus. Berkas disimpan pada disk `public` Laravel (`storage/app/public`).

### 4.3. Pembentukan dan Finalisasi PDRB

1. Admin memilih tahun dan triwulan pada halaman Pembentukan PDRB.
2. Sistem menampilkan status ketersediaan empat jenis data pendukung.
3. Admin menekan **Hitung PDRB**. Sistem menghasilkan PDRB ADHB dan ADHK, biaya antara, kontribusi sektor, serta pertumbuhan QoQ dan YoY.
4. Pada halaman Finalisasi, hasil dijadikan PDRB final. Finalisasi dapat dibatalkan, dan riwayat versi serta komentar dapat ditelusuri.

Rumus utama:

```text
NTB = Output − Biaya Antara
ADHK = ADHB × 100 / IHP
YoY(i) = (ADHK(i,t) − ADHK(i,t−1)) / ADHK(i,t−1) × 100
```

dengan `t−1` adalah periode yang sama pada tahun sebelumnya.

### 4.4. Tabel IO dan Simulasi IO

Admin membuat dan mengelola dataset IO (transaksi antarsektor, permintaan akhir, dan output), lalu melihat Matriks A dan Matriks Leontief. Operator dan Admin menjalankan simulasi dengan memilih dataset, satu atau beberapa sektor penerima stimulus, dan nilai stimulus (miliar rupiah).

```text
ΔX = (I − A)⁻¹ ΔY = L · ΔY
ΔNTB(i) = ΔX(i) × (1 − r(i))
m_NTB = ΔNTB_total / ΔY_total
```

Hasil simulasi dapat disimpan sebagai riwayat, dibandingkan antarskenario, dan diekspor ke Excel atau PDF.

### 4.5. Dashboard Analitik

Dashboard menampilkan total ADHB dan ADHK, pertumbuhan QoQ dan YoY, lima sektor teratas dan terbawah, tren dan pertumbuhan antarperiode, struktur sektor, heatmap, serta kontribusi per kelompok ekonomi. Data diambil dari PDRB final dan dapat difilter menurut tahun dan triwulan. Dashboard dapat diekspor ke PDF.

### 4.6. Laporan

Halaman Download menyusun laporan PDRB menurut kategori, tahun, dan triwulan dalam format PDF atau CSV, dengan pilihan kolom yang dapat dikustomisasi.

### 4.7. Pengguna dan Profil

Registrasi mandiri, login, logout, ubah profil, foto profil, dan ubah kata sandi tersedia untuk semua pengguna. Admin mengelola pengguna: mengubah peran dan tim, mengedit, dan menghapus.

## 5. Arsitektur

Si-PRABU memakai pola Model–View–Controller (MVC) Laravel.

```text
Peramban web
    |
    v
Routing + Middleware (auth, verified, role)
    |
    v
Controller  --->  Model (Eloquent ORM)  --->  MySQL
    |
    v
View (Blade)        File storage (dokumen pendukung, foto profil)
```

## 6. Tech Stack

| Lapisan | Teknologi |
|---|---|
| Backend | PHP ^8.1, Laravel ^10.10 |
| Database | MySQL |
| Templat tampilan | Blade |
| Frontend | Bootstrap, jQuery, Chart.js, DataTables, Select2, SweetAlert2, Alpine.js, Tailwind CSS, Vite |
| Autentikasi | Laravel Breeze, Laravel Sanctum |
| Ekspor dan impor | Laravel Excel (maatwebsite/excel), PhpSpreadsheet, barryvdh/laravel-dompdf, mPDF, PhpWord |
| Penyimpanan berkas | Disk `public` Laravel |

## 7. Struktur Repository

```text
app/
├── Console/Commands/        # db:truncate
├── Enums/
├── Exports/                 # Ekspor Excel (IHP, Harga-Produksi, Rasio, WIP/CBR)
├── Http/
│   ├── Controllers/         # Pdrb, FinalisasiPdrb, IO, Simulasi, Dashboard, Download, Document, ManageUser, dll.
│   └── Middleware/          # RoleMiddleware
├── Imports/                 # Impor Excel
└── Models/
database/
├── migrations/
└── seeders/                 # Seeder dan data awal (data/*.json, data/role.csv)
resources/
├── views/                   # Blade: dashboard, pdrb, finalisasi, io, simulasi, documents, download, manage, dll.
├── css/ dan js/             # Aset Vite
routes/
├── web.php                  # Rute utama dan pembatasan peran
└── auth.php                 # Rute autentikasi
public/                      # Aset publik (CSS, JS, gambar, vendor)
```

## 8. Menjalankan Aplikasi

### 8.1. Prasyarat

- PHP 8.1 atau lebih baru dengan ekstensi `gd`, `fileinfo`, `zip`, `xml`, `mbstring`, `pdo_mysql`.
- Composer.
- Node.js dan npm: `<ISI VERSI NODE.JS>`.
- MySQL: `<ISI VERSI MYSQL>`. Aplikasi memakai sintaks khusus MySQL (misalnya `SHOW TABLES`), sehingga SQLite tidak didukung.

### 8.2. Instalasi

```bash
git clone https://github.com/bagusrega4/entry-pdrb.git
cd entry-pdrb

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Pada Windows PowerShell, gunakan `Copy-Item .env.example .env`.

### 8.3. Konfigurasi `.env`

Buat database kosong, lalu sesuaikan variabel berikut:

```env
APP_NAME=Si-PRABU
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<NAMA DATABASE>
DB_USERNAME=<USER>
DB_PASSWORD=<PASSWORD>

FILESYSTEM_DISK=local
```

Variabel surel (`MAIL_*`) diperlukan bila fitur lupa kata sandi atau verifikasi surel dipakai. Untuk produksi, setel `APP_ENV=production` dan `APP_DEBUG=false`.

### 8.4. Migrasi, Seeder, dan Storage

```bash
php artisan migrate --seed
php artisan db:seed --class=EconomicGroupSeeder
php artisan storage:link
```

`migrate --seed` mengisi peran, pegawai, pengguna, komoditas, indikator, satuan, triwulan, dan tim dari berkas pada `database/seeders/data/`. Kelompok ekonomi (dipakai dashboard) tidak ikut dipanggil oleh `DatabaseSeeder`, sehingga dijalankan terpisah. Pengguna hasil seeder memakai kata sandi awal yang sama; lihat bagian 12.

### 8.5. Menjalankan

```bash
npm run build          # produksi
php artisan serve      # http://localhost:8000
```

Untuk pengembangan, jalankan `npm run dev` pada terminal terpisah.

## 9. Pengujian

Evaluasi pada 29 September 2026:

| Kelompok fitur | Use case | Kasus uji | Sesuai |
|---|---|---|---|
| Autentikasi dan akun | UC-01 s.d. UC-03 | 8 | 8 |
| Dashboard dan simulasi IO | UC-04 s.d. UC-06 | 7 | 7 |
| Data pendukung PDRB | UC-07 s.d. UC-10 | 9 | 9 |
| Dokumen pendukung | UC-11 | 3 | 3 |
| Tabel IO | UC-12 | 3 | 3 |
| Pembentukan dan finalisasi PDRB | UC-13 dan UC-14 | 3 | 3 |
| Laporan | UC-15 | 3 | 3 |
| Pengguna dan hak akses | UC-16 dan UC-17 | 5 | 5 |
| **Total** | **17 use case** | **41** | **41** |

User Acceptance Testing (UAT) dilakukan terhadap 11 pegawai BPS Kabupaten Kediri dengan kuesioner 25 pernyataan berskala Likert 1–5. Rata-rata keseluruhan 4,77 dari 5 (95,42%), kategori sangat baik. Aspek terendah adalah kecepatan respons sistem (89,09%).

Pengujian keamanan dan beban (load testing) belum dilakukan.

## 10. Data

Dataset awal (komoditas, indikator, satuan, triwulan, tim, dan daftar pegawai) berada pada `database/seeders/data/`. Data PDRB, dokumen, dan tabel IO dimasukkan melalui aplikasi. Hak atas data dan dokumen BPS tetap mengikuti ketentuan BPS.

## 11. Saran Pengembangan

- Optimasi kueri basis data dan pemrosesan latar belakang untuk meningkatkan kecepatan respons.
- Pengujian keamanan dan beban.
- Analisis forward dan backward linkage, peramalan PDRB, dan notifikasi kelengkapan data pada modul IO.
- Modul PDRB menurut pendekatan pengeluaran bila dibutuhkan.
- Versi aplikasi mobile.

## 12. Batasan dan Keamanan

- Si-PRABU adalah prototipe penelitian untuk tim Nerwilis BPS Kabupaten Kediri, bukan layanan resmi BPS.
- Seeder `UserSeeder` membuat seluruh pengguna awal dengan kata sandi yang sama dan tertulis pada kode. Ganti seluruh kata sandi, atau hapus akun yang tidak diperlukan, sebelum aplikasi diakses publik.
- Berkas `database/seeders/data/pegawai.json`, `users.json`, dan berkas `.xls` pada folder `database/` memuat data kepegawaian. Pertimbangkan untuk tidak menyimpannya di repository publik.
- Registrasi mandiri terbuka pada rute `/register` dan akun baru otomatis berperan Operator. Tinjau pengguna baru melalui menu Kelola Pengguna.
- Rute `/check-auth` hanya untuk pemeriksaan pengembangan dan sebaiknya dinonaktifkan pada produksi.
- Jangan memasukkan berkas `.env` atau kredensial ke repository.
- Pengujian terbatas pada fungsionalitas dan penerimaan pengguna.

## 13. Lisensi

`<ISI LISENSI KODE>`. Repository belum memuat berkas `LICENSE`; `composer.json` hanya mewarisi metadata MIT dari kerangka Laravel. Lisensi kode tidak otomatis berlaku terhadap data dan dokumen BPS.
