<p align="center">
  <img src="public/image/niskala sammudra.png" alt="Niskala Samudra Logo" width="220" style="border-radius: 16px;">
</p>

<h1 align="center">🌊 Niskala Samudra</h1>
<p align="center">
  <strong>Sistem Informasi Restoran & Pemesanan Kuliner Seafood Terintegrasi</strong><br>
  <em>Solusi Manajemen Restoran Modern: Dari Reservasi Meja, Dapur, hingga Pengantaran Kurir Real-Time</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/License-MIT-gold?style=for-the-badge" alt="License">
</p>

---

## 📌 Tentang Aplikasi

**Niskala Samudra** adalah platform web aplikasi manajemen restoran dan pemesanan makanan laut (_seafood_) terpadu yang dirancang untuk memberikan pengalaman bersantap eksklusif bagi pelanggan sekaligus mengoptimalkan efisiensi operasional staf restoran.

Aplikasi ini mengintegrasikan seluruh alur bisnis kuliner dari hulu ke hilir:

- **Pelanggan:** Memilih menu, memesan makanan baik makan di tempat (_Dine-In_) maupun pesan antar (_Delivery_), melakukan pembayaran via QRIS/COD, dan memantau status pesanan secara _live tracking_.
- **Dapur (_Kitchen Display_):** Menerima dan memproses antrean pesanan secara langsung dengan sistem notifikasi pintar.
- **Kurir (_Delivery Fleet_):** Mengelola penugasan pengantaran, rute otomatis via Google Maps, chat pelanggan via WhatsApp, pencatatan waktu tempuh akurat (WIB), hingga konfirmasi serah terima _Proof of Delivery_ (POD).
- **Administrator:** Mengelola inventori menu, memantau laporan pendapatan, mengatur akun staf, serta menganalisis performa penjualan restoran.

---

## ✨ Fitur-Fitur Utama

### 1. 🍽️ Pengalaman Pelanggan (_Customer Experience_)

- **Hero Carousel & Showcase:** Banner promosi beranda yang dinamis dan elegan dengan navigasi responsif.
- **Katalog Menu Interaktif:** Filter kategori hidangan (Kepiting, Udang, Cumi, Ikan, Lobster, Minuman) dan pencarian _live search_.
- **Keranjang Belanja (_Slide-over Cart Drawer_):** Manajemen keranjang pesanan _real-time_ berbasis Alpine.js dengan kontrol porsi dan pengecekan stok otomatis.
- **Fleksibilitas Layanan Pemesanan:**
    - **Makan di Tempat (_Dine-In_):** Reservasi langsung dengan pemilihan nomor meja.
    - **Pesan Antar (_Delivery_):** Pengisian alamat lengkap dengan **Patokan Alamat** (_landmark_) untuk memudahkan kurir.
- **Pilihan Metode Pembayaran:**
    - **QRIS Otomatis:** Dilengkapi instruksi pembayaran, timer kedaluwarsa, dan fitur unggah foto bukti transfer.
    - **Bayar di Tempat (_Cash on Delivery / COD_):** Pembayaran tunai saat hidangan diserahkan oleh kurir.
- **Pelacakan Langsung (_Live Order Tracking_):**
    - Stepper alur dinamis 5 tahap khusus _Delivery_ (`Diterima` $\rightarrow$ `Dapur` $\rightarrow$ `Dikemas` $\rightarrow$ `Perjalanan` $\rightarrow$ `Tiba`) dan 3 tahap untuk _Dine-In_.
    - Kartu kurir bertugas, waktu berangkat, estimasi waktu tiba (ETA), durasi perjalanan, dan bukti foto serah terima.
- **Struk Digital (_Digital Receipt_):** Struk transaksi terverifikasi dengan rincian biaya, ongkos kirim, dan catatan pesanan.

### 2. 👨‍🍳 Operasional Dapur (_Kitchen Display System_)

- **Antrean Masak Real-Time:** Menampilkan daftar pesanan yang perlu dimasak dengan _auto-refresh_ berkala.
- **Transisi Status Cerdas:** Tombol otomatis membedakan _"Selesai Dimasak & Sajikan"_ untuk Dine-In dan _"Selesai Dimasak (Siap Diantar)"_ untuk pesanan Delivery.
- **Instruksi Khusus:** Penanda visual khusus jika pelanggan menyertakan catatan alergi atau permintaan racikan khusus.

### 3. 🛵 Pusat Kendali Kurir & Pengantaran (_Delivery Dispatch_)

- **Tab Status Kurir:** Filter antrean pesanan dalam tab _Semua_, _Siap Diantar_, dan _Sedang Jalan_.
- **Alat Operasional Cepat:**
    - **Rute Google Maps:** Tombol pintas navigasi rute langsung ke koordinat/alamat pemesan.
    - **WhatsApp Direct Chat:** Kirim pesan konfirmasi pengantaran ke nomor pelanggan dengan _prefilled message_.
- **Penugasan Mandiri (_Self-Assignment_):** Kurir dapat mengklaim pesanan via tombol _"Ambil & Mulai Antar"_ yang otomatis mencatat ID kurir dan waktu berangkat.
- **Serah Terima Berfoto (_Proof of Delivery / POD_):**
    - Input nama penerima langsung di lokasi.
    - Unggah foto dokumentasi serah terima makanan.
    - Peringatan wajib tagih tunai untuk pesanan COD sebelum diselesaikan.
- **Pencatatan Waktu Akurat:** Menggunakan zona waktu Indonesia (**WIB / Asia/Jakarta**) lengkap dengan durasi tempuh riil.

### 4. 👑 Panel Manajemen Admin (_Backoffice_)

- **Dashboard Statistik:** Ringkasan pendapatan harian/bulanan, jumlah pesanan aktif, total menu, dan staf.
- **Kelola Menu & Kategori:** CRUD menu lengkap dengan foto, deskripsi, harga, dan kuota stok yang otomatis berkurang saat dipesan serta kembali jika dibatalkan (_auto stock restore_).
- **Kelola Transaksi:** Audit seluruh transaksi masuk, verifikasi bukti transfer QRIS, dan kontrol status manual.
- **Kelola Pengguna:** Manajemen data pengguna dan pembagian peran (_role-based access control_).
- **Kelola Banner Promosi:** Pengaturan slider gambar dan promosi pada halaman utama.
- **Laporan Penjualan:** Rekapitulasi laporan pendapatan berbasis rentang tanggal dengan fitur cetak struk/laporan bersih.

---

## 🛠️ Arsitektur & Teknologi

| Komponen                  | Teknologi yang Digunakan                                                 |
| :------------------------ | :----------------------------------------------------------------------- |
| **Backend Framework**     | [Laravel 11.x](https://laravel.com)                                      |
| **Bahasa Pemrograman**    | PHP 8.2+                                                                 |
| **Database**              | MySQL 8.0 / MariaDB                                                      |
| **Frontend Styling**      | [Tailwind CSS 3.x](https://tailwindcss.com) & [Vite](https://vitejs.dev) |
| **Clientside Reactivity** | [Alpine.js 3.x](https://alpinejs.dev)                                    |
| **Iconography**           | [FontAwesome 6 Pro/Free](https://fontawesome.com)                        |
| **Typography**            | Cinzel (Serif) & Instrument Sans (Modern Web Typography)                 |
| **Testing & Automation**  | Python 3 + Playwright _(Auto-Capture Documentation)_                     |

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan aplikasi Niskala Samudra di komputer lokal Anda:

### 1. Prasyarat Sistem

- PHP $\ge$ 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `openssl`)
- Composer $\ge$ 2.x
- Node.js $\ge$ 18.x & NPM
- Server MySQL / MariaDB (misal: XAMPP, Laragon, atau native service)

### 2. Kloning Repository

```bash
git clone https://github.com/username/Seafoodrest.git
cd Seafoodrest
```

### 3. Instalasi Dependensi PHP & Node.js

```bash
composer install
npm install
```

### 4. Konfigurasi Lingkungan (_Environment_)

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan koneksi database Anda:

```env
APP_NAME="Niskala Samudra"
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=SeafoodRest
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key & Storage Link

```bash
php artisan key:generate
php artisan storage:link
```

### 6. Migrasi Database & Seeder

Pastikan database dengan nama yang ditentukan di `.env` sudah dibuat di MySQL, lalu jalankan:

```bash
php artisan migrate --seed
```

### 7. Kompilasi Aset Frontend

```bash
npm run build
# Atau untuk mode pengembangan (hot-reload):
# npm run dev
```

### 8. Jalankan Server Aplikasi

```bash
php artisan serve
```

Akses aplikasi melalui peramban web di: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Bawaan (_Default Credentials_)

Setelah proses seeding selesai, Anda dapat menggunakan akun bawaan berikut untuk pengujian:

| Role / Peran        | Email                        | Kata Sandi | Hak Akses Utama                                                |
| :------------------ | :--------------------------- | :--------- | :------------------------------------------------------------- |
| **Administrator**   | `admin@gmail.com`            | `admin123` | Akses penuh: Panel Admin, Laporan, CRUD Menu, Dapur, & Kurir   |
| **Karyawan / Staf** | _(Didaftarkan via Admin)_    | -          | Akses Operasional: Kasir, Dapur Masak, Pengantaran Kurir, Stok |
| **Pelanggan**       | _(Bebas Registrasi via Web)_ | -          | Pemesanan Menu, Riwayat Transaksi, Live Tracking               |

---

## 📸 Dokumentasi & Auto-Capture Tool

Proyek ini telah dilengkapi dengan tool otomatisasi tangkapan layar (**Auto-Capture Generator**) berbasis **Python & Playwright** untuk keperluan penyusunan laporan, skripsi, atau proposal tugas akhir.

Tool ini dapat menangkap lebih dari 30 halaman sistem secara otomatis (termasuk alur klik menu, isi checkout delivery, dapur, kurir, hingga laporan admin).

Untuk menjalankannya:

```bash
# Pastikan server Laravel aktif di terminal pertama (php artisan serve)
# Pada terminal kedua:
cd auto_capture
./run.sh        # Untuk pengguna Linux / macOS
run.bat         # Untuk pengguna Windows (atau cukup Double-Click run.bat)
```

Semua hasil tangkapan layar beresolusi tinggi akan tersimpan rapi dan terkategori di dalam folder `auto_capture/screenshots/`.

---

## 👨‍💻 Pengembang Proyek

Proyek ini dikembangkan oleh:

- **Nama:** Aditya Mukti Firmansyah
- **Institusi:** SMK Wikrama 1 Jepara
- **Spesialisasi:** Web Development & Full-Stack Engineering

---

## 📄 Lisensi

Aplikasi web **Niskala Samudra** dirilis di bawah lisensi [MIT License](LICENSE).
Semua orang diperbolehkan menggunakan, memodifikasi, dan mengembangkan kode ini untuk keperluan edukasi maupun komersial.

<p align="center">
  <sub>Dibuat dengan ❤️ dan dedikasi untuk industri kuliner nusantara.</sub>
</p>
