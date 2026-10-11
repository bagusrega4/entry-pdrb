# Paket Pengujian Black Box Si-PRABU (Jalur 1)

Berisi 318 tes otomatis (Feature Test Laravel) yang dipetakan satu-satu ke ID kasus uji di tabel Word,
ditambah 10 tes "temuan" (T-01 s.d. T-10) untuk kelemahan yang terlihat saat membaca kode.
Sebanyak 6 kasus uji tidak diotomasi dan harus diuji manual (lihat bagian bawah).

## 1. Pasang

Ekstrak ZIP ini ke folder root proyek Laravel (sejajar dengan `artisan`). Isinya:

    tests/Feature/BlackBox/*      -> tes
    phpunit.blackbox.xml          -> konfigurasi khusus (file phpunit.xml Anda TIDAK diubah)
    .env.testing.contoh           -> contoh konfigurasi database uji
    tools/isi_hasil.py            -> pemeta hasil tes ke ID tabel

## 2. Siapkan database uji (WAJIB, demi keamanan data)

Tes memakai `RefreshDatabase` yang menjalankan `migrate:fresh`. Jangan sampai mengarah ke database asli.

1. Buat database kosong: `CREATE DATABASE si_prabu_test;`
2. Salin `.env.testing.contoh` menjadi `.env.testing`, isi `APP_KEY` (ambil dari `.env`) dan user/password MySQL.
3. Pengaman berlapis: `phpunit.blackbox.xml` memaksa `DB_DATABASE=si_prabu_test`, dan kelas dasar tes
   menolak berjalan jika nama database tidak mengandung kata "test".

Tes harus memakai **MySQL** (bukan SQLite) karena aplikasi memakai sintaks khusus MySQL.

## 3. Prasyarat PHP

Ekstensi `gd` (pembuatan gambar palsu untuk uji unggah foto), `fileinfo`, `zip`, `xml`, `mbstring`.
Di Laragon/XAMPP, aktifkan `extension=gd` pada php.ini bila belum.

## 4. Jalankan

    php artisan config:clear
    vendor/bin/phpunit -c phpunit.blackbox.xml --testsuite BlackBox --log-junit hasil-junit.xml

Boleh dijalankan per bagian, mis. `--filter Uc01RegistrasiTest`.

## 5. Kirim hasilnya

Kirim ke Claude: file `hasil-junit.xml` **dan** teks kegagalan dari terminal.
Kegagalan perlu ditinjau satu per satu karena bisa berarti (a) tes salah karena asumsi saya belum
pernah diverifikasi dengan menjalankan aplikasi, atau (b) memang kelemahan aplikasi.
Setelah itu tabel Word diisi otomatis.

Opsional, jika ingin memetakan sendiri: `python3 tools/isi_hasil.py hasil-junit.xml`
menghasilkan `hasil.json` dan `laporan_pengujian.txt`.

## 6. Kasus yang harus diuji manual di browser (6 kasus)

| ID | Kasus | Alasan tidak diotomasi |
|----|-------|------------------------|
| 02.10 | Tampil/sembunyi password | Perilaku JavaScript di browser |
| 17.09 | Unduh PDF satu kategori | Kode memakai `exit` dan `header()` langsung, mematikan proses PHPUnit |
| 17.10 | Unduh PDF semua kategori | Sama seperti di atas |
| 17.11 | Unduh CSV satu kategori | Sama seperti di atas |
| 17.12 | Unduh CSV semua kategori | Sama seperti di atas |
| 17.15 | Kategori tanpa komoditas | Jalur PDF; dari kode, `getCategoryCommodities()` selalu menyertakan kategori itu sendiri sehingga pesan "Tidak ada data komoditas!" kemungkinan tidak pernah muncul |

Catatan: tes otomatis memverifikasi logika, validasi, hak akses, dan isi respons di tingkat HTTP.
Tampilan visual (tata letak, grafik, warna) tidak tercakup dan tetap perlu dilihat sendiri, misalnya
untuk 06.03, 06.05, 06.06 dan 07.22 yang hanya memeriksa data/endpoint-nya.

## 7. Tes temuan (TemuanTest)

Tes ini menyatakan perilaku yang seharusnya terjadi. Jika GAGAL, ada kelemahan pada aplikasi:

| Kode | Yang diperiksa |
|------|----------------|
| T-01 | Sektor baru lewat UI mendapat level berbeda dari sektor hasil seeder (0 vs 1) |
| T-02 | Unggah dokumen tanpa `modul` tidak divalidasi (berujung galat DB) |
| T-03 | Unggah dokumen tidak membatasi jenis file (mis. .php/.exe) |
| T-04 | Profil non-BPS: data pegawai yang berubah bisa milik pengguna lain |
| T-05 | Hapus pengguna non-BPS: bisa menghapus pegawai non-BPS lain |
| T-06 | Simulasi membaca `pdrb_results` (belum final) walau pesan menyebut finalisasi |
| T-07 | Hitung PDRB: sektor dengan rasio biaya antara >= 100% dilewati tanpa peringatan |
| T-08 | Route `finalisasi.batal` dan `finalisasi.detail` tidak punya method di controller |
| T-09 | Folder `lang/` tidak ada sehingga pesan bawaan Laravel tampil sebagai kunci mentah |
| T-10 | Harga negatif diterima |
