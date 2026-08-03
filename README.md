# Sistem Informasi Rekam Medis OQ Clinic Dentist

Sistem Informasi Rekam Medis OQ Clinic Dentist adalah aplikasi berbasis *web* yang digunakan untuk membantu proses pengelolaan data pasien, rekam medis, odontogram, resep obat, pembayaran, dan laporan pada klinik gigi.

Sistem ini dikembangkan sebagai bagian dari penelitian skripsi dengan judul:

**Perancangan Sistem Informasi Rekam Medis Berbasis Web Menggunakan Metode Rapid Application Development (RAD) pada OQ Clinic Dentist**

## Tentang Sistem

Sistem ini dirancang untuk mempermudah proses pelayanan dan pencatatan rekam medis pasien di OQ Clinic Dentist. Dengan adanya sistem ini, proses pengelolaan data pasien dan rekam medis dapat dilakukan secara lebih terstruktur, cepat, dan terintegrasi.

Fitur utama pada sistem ini meliputi:

1. Dashboard
2. Manajemen Data Pasien
3. Rekam Medis Pasien
4. Pemeriksaan Dokter
5. Odontogram
6. Resep dan Pemberian Obat
7. Pembayaran
8. Laporan Pembayaran
9. Master Data

   * Data Dokter
   * Data Tindakan
   * Data ICD / Diagnosa
   * Data Obat
   * Data Pengguna

## Hak Akses

Sistem ini memiliki hak akses pengguna sesuai dengan kebutuhan operasional klinik, yaitu:

1. Admin
   Admin memiliki hak akses untuk mengelola data pasien, rekam medis awal, data dokter, tindakan, ICD/diagnosa, obat, pembayaran, laporan, dan pengguna sistem.

2. Dokter
   Dokter memiliki hak akses untuk melihat data pasien yang perlu diperiksa, menginput hasil pemeriksaan, mengelola odontogram, menginput diagnosis, tindakan, dan resep obat.

## Alur Sistem

Alur penggunaan sistem secara umum adalah sebagai berikut:

1. Admin melakukan login ke dalam sistem.
2. Admin menambahkan data pasien.
3. Admin membuat rekam medis awal berdasarkan keluhan pasien.
4. Dokter melakukan pemeriksaan pasien.
5. Dokter menginput hasil pemeriksaan, diagnosis, tindakan, odontogram, dan resep obat apabila diperlukan.
6. Admin mengelola pembayaran pasien.
7. Admin mencetak nota pembayaran.
8. Admin melihat dan mencetak laporan pembayaran.

## Odontogram

Sistem ini mendukung pencatatan odontogram sebagai bagian dari rekam medis gigi. Odontogram digunakan untuk mencatat kondisi gigi pasien berdasarkan elemen gigi, hasil pemeriksaan, diagnosis, dan tindakan yang diberikan oleh dokter.

Fitur odontogram membantu dokter dalam melihat riwayat kondisi gigi pasien secara lebih jelas dan terstruktur.

## Teknologi yang Digunakan

Sistem ini dibangun menggunakan beberapa teknologi berikut:

1. PHP
2. Laravel
3. MySQL
4. HTML
5. CSS
6. JavaScript
7. Bootstrap
8. XAMPP

## Metode Pengembangan Sistem

Metode pengembangan sistem yang digunakan adalah **Rapid Application Development (RAD)**. Metode RAD digunakan karena mendukung proses pengembangan sistem secara cepat, bertahap, dan melibatkan pengguna dalam proses perancangan serta evaluasi sistem.

Tahapan pengembangan sistem meliputi:

1. Analisa kebutuhan sistem
2. Perancangan sistem
3. Pembuatan program
4. Pengujian sistem
5. Implementasi dan evaluasi

## Instalasi Sistem

Langkah-langkah menjalankan sistem pada komputer lokal:

1. Clone atau download project.
2. Pindahkan folder project ke direktori `htdocs` pada XAMPP.
3. Buka terminal atau Command Prompt pada folder project.
4. Jalankan perintah:

```bash
composer install
```

5. Salin file `.env.example` menjadi `.env`.
6. Atur konfigurasi database pada file `.env`.
7. Jalankan perintah:

```bash
php artisan key:generate
```

8. Jalankan migrasi database:

```bash
php artisan migrate
```

9. Jalankan server Laravel:

```bash
php artisan serve
```

10. Buka sistem melalui browser:

```bash
http://127.0.0.1:8000
```

## Akun Pengguna

Contoh akun pengguna dapat disesuaikan dengan data pada database masing-masing.

Hak akses utama sistem:

1. Admin
2. Dokter

## Tujuan Pengembangan

Tujuan dari pengembangan sistem ini adalah untuk menghasilkan sistem informasi rekam medis berbasis *web* yang dapat membantu OQ Clinic Dentist dalam mengelola data pasien, data rekam medis, odontogram, resep obat, pembayaran, dan laporan secara lebih efektif dan efisien.

## Pengembang

Sistem ini dikembangkan oleh:

**Hendrikwan Putra Zai**

Program Studi Sistem Informasi
Fakultas Teknologi Informasi
Universitas Nusa Mandiri

## Catatan

Sistem ini dikembangkan untuk kebutuhan penelitian skripsi dan dapat dikembangkan lebih lanjut sesuai kebutuhan operasional klinik.
