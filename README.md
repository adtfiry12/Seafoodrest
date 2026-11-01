<p align="center">
  <img src="public/image/niskala sammudra.png" alt="Logo Niskala Samudra" width="220">
</p>

<h1 align="center">Niskala Samudra</h1>

<p align="center">
  Sistem informasi restoran seafood untuk katalog menu, pemesanan, pembayaran,
  dan operasional restoran dalam satu aplikasi.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3+">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS 4">
  <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=flat-square&logo=alpinedotjs&logoColor=white" alt="Alpine.js 3">
</p>

## Tentang proyek

Niskala Samudra adalah aplikasi web restoran yang menghubungkan pengalaman pelanggan
dengan kebutuhan operasional di belakang layar. Pelanggan dapat melihat menu,
membuat pesanan makan di tempat atau delivery, membayar, dan memantau perkembangan
pesanan. Admin, karyawan, dan kurir memiliki ruang kerja masing-masing untuk
memproses pesanan sampai selesai.

Aplikasi ini menggunakan Bahasa Indonesia dan zona waktu `Asia/Jakarta` sebagai
konfigurasi default.

## Fitur

### Pelanggan dan pengunjung

- Halaman beranda dengan slide promosi dan menu pilihan.
- Halaman tentang restoran dan kontak, termasuk tautan WhatsApp serta lokasi.
- Katalog menu aktif dengan:
  - pencarian berdasarkan nama menu;
  - filter berdasarkan kategori;
  - pagination;
  - halaman detail dan menu terkait.
- Keranjang belanja berbasis Alpine.js dengan kontrol jumlah dan batas stok.
- Checkout sebagai tamu untuk pesanan non-COD.
- Pilihan layanan:
  - **Makan di Tempat** dengan pilihan datang sekarang atau mengisi informasi
    reservasi berupa jam kedatangan dan jumlah orang;
  - **Delivery** dengan alamat dan patokan alamat.
- Pilihan pembayaran sesuai jenis layanan:
  - tunai di kasir untuk makan di tempat;
  - QRIS dan kanal pembayaran Midtrans;
  - COD untuk delivery dan pelanggan yang sudah login.
- Halaman pembayaran QRIS dengan timer kedaluwarsa, pengecekan status berkala,
  callback Midtrans, dan unggah bukti pembayaran.
- Riwayat dan detail pesanan untuk pelanggan yang login.
- Pembatalan pesanan pada status yang masih memenuhi syarat.
- Status pesanan, status pembayaran, estimasi pengantaran, dan struk digital
  bertanda tangan.

### Operasional karyawan

- Dashboard operasional dengan ringkasan pesanan aktif dan stok menipis.
- POS/kasir untuk membuat pesanan:
  - dine-in;
  - takeaway;
  - delivery.
- Pencarian pelanggan terdaftar atau input pelanggan walk-in.
- Antrean pesanan baru dengan sinkronisasi status pembayaran Midtrans.
- Tampilan antrean dapur untuk pesanan yang sedang dimasak.
- Perubahan status pesanan dari diterima, dimasak, siap diantar, sampai selesai.
- Pengelolaan stok menu dan pembaruan jumlah stok.
- Notifikasi jumlah antrean pada navigasi yang diperbarui secara berkala.

### Operasional kurir

- Dashboard dan antrean delivery.
- Melihat pesanan yang siap diantar atau sedang diantar.
- Mengambil pesanan dan mencatat waktu keberangkatan.
- Mencatat waktu sampai, nama penerima, dan bukti pengantaran.
- Validasi bahwa pembayaran COD sudah diterima sebelum delivery diselesaikan.

### Panel admin

- Dashboard statistik pendapatan, pesanan baru, menu, pelanggan, menu terlaris,
  dan pelanggan dengan pembelian terbanyak.
- CRUD kategori menu.
- CRUD menu, termasuk harga, deskripsi, gambar, stok, dan status aktif.
- CRUD slide promosi di halaman beranda.
- CRUD pengguna dan pengaturan peran.
- Pengelolaan transaksi, perubahan status, detail pembayaran, dan cetak struk.
- Laporan penjualan berdasarkan rentang tanggal.
- Cetak laporan penjualan.

### Perilaku transaksi dan pembayaran

- Stok dikunci dan dikurangi dalam transaksi database saat pesanan dibuat.
- Stok dikembalikan ketika pesanan dibatalkan.
- Pesanan QRIS yang belum lunas dibatalkan otomatis setelah satu jam dan stoknya
  dikembalikan.
- Delivery menggunakan ongkos kirim tetap sebesar **Rp15.000**.
- Midtrans mendukung kanal pembayaran yang tersedia pada konfigurasi akun,
  seperti QRIS, e-wallet, virtual account, transfer bank, kartu, dan gerai retail.

> **Catatan:** pelacakan delivery pada aplikasi berupa status, waktu berangkat,
> waktu tiba, estimasi, dan bukti pengantaran. Aplikasi belum menyediakan pelacakan
> GPS atau posisi kurir secara langsung di peta.

## Peran pengguna

| Peran | Akses utama |
| --- | --- |
| Pengunjung | Melihat katalog, mendaftar, login, dan membuat pesanan non-COD |
| Pelanggan | Checkout, pembayaran, riwayat pesanan, pembatalan, profil, dan struk |
| Karyawan | POS, pesanan baru, dapur, stok, dan pemrosesan status pesanan |
| Kurir | Antrean delivery, mulai pengantaran, dan konfirmasi serah terima |
| Admin | Dashboard, seluruh pengelolaan data, laporan, serta akses operasional |

## Teknologi

| Area | Teknologi |
| --- | --- |
| Backend | PHP 8.3+ dan Laravel 13 |
| Database default | SQLite |
| Database alternatif | MySQL atau MariaDB melalui konfigurasi Laravel |
| Frontend | Blade, Tailwind CSS 4, Alpine.js 3, dan Vite |
| Pembayaran | Midtrans Snap dan callback/webhook Midtrans |
| Penyimpanan file | Laravel filesystem dengan private storage untuk bukti pembayaran |
| Testing | Pest dan PHPUnit |

## Menjalankan secara lokal

### Prasyarat

- PHP 8.3 atau lebih baru dengan ekstensi umum Laravel, termasuk `pdo`,
  `mbstring`, `fileinfo`, dan `openssl`;
- Composer 2 atau lebih baru;
- Node.js dan npm.

### Instalasi

1. Kloning repository dan masuk ke folder proyek:

   ```bash
   git clone https://github.com/adtfiry12/Seafoodrest.git
   cd Seafoodrest
   ```

2. Pasang dependency PHP dan JavaScript:

   ```bash
   composer install
   npm install
   ```

3. Siapkan file environment dan application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Jalankan migrasi dan seeder:

   ```bash
   php artisan migrate --seed
   ```

   Konfigurasi bawaan menggunakan SQLite. Untuk MySQL atau MariaDB, ubah
   `DB_CONNECTION` dan variabel `DB_*` di `.env` sebelum menjalankan migrasi.

5. Siapkan akses file upload:

   ```bash
   php artisan storage:link
   ```

6. Build aset frontend:

   ```bash
   npm run build
   ```

7. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

   Buka `http://127.0.0.1:8000` di browser.

Untuk pengembangan frontend dengan hot reload, gunakan `npm run dev` sebagai
pengganti `npm run build`.

### Setup satu perintah

Composer menyediakan script setup yang memasang dependency, membuat `.env`,
menghasilkan application key, menjalankan migrasi, memasang dependency npm, dan
membangun aset:

```bash
composer run setup
php artisan storage:link
```

## Konfigurasi Midtrans

Pembayaran digital memerlukan kredensial Midtrans. Isi variabel berikut di `.env`:

```env
MIDTRANS_MERCHANT_ID=
MIDTRANS_CLIENT_KEY=
MIDTRANS_SERVER_KEY=
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

Gunakan `MIDTRANS_IS_PRODUCTION=false` untuk lingkungan sandbox. Jangan commit
nilai `MIDTRANS_SERVER_KEY` atau kredensial lain ke repository.

Tanpa kredensial Midtrans, fitur katalog, akun, POS, dan alur pesanan non-digital
tetap dapat dikembangkan, tetapi pembayaran Midtrans tidak akan berfungsi.

## Akun awal

Seeder membuat satu akun admin untuk lingkungan lokal:

| Peran | Email | Kata sandi |
| --- | --- | --- |
| Admin | `admin@gmail.com` | `admin123` |

Segera ganti kata sandi tersebut pada lingkungan yang digunakan bersama atau
lingkungan produksi. Pelanggan dapat membuat akun melalui halaman registrasi,
sedangkan akun karyawan dan kurir dikelola dari panel admin.

## Perintah pengembangan

```bash
# Menjalankan test
php artisan test --compact

# Memformat kode PHP yang berubah
vendor/bin/pint --dirty --format agent

# Menjalankan server Vite
npm run dev
```

## Struktur fitur utama

```text
app/
├── Http/Controllers/
│   ├── Back/       # autentikasi, admin, POS, dapur, kurir, laporan
│   └── Front/      # halaman publik, checkout, pembayaran, pesanan
├── Models/         # pengguna, menu, kategori, transaksi, dan detail transaksi
└── Services/       # integrasi pembayaran Midtrans
database/
├── migrations/     # struktur tabel dan perubahan skema
└── seeders/        # akun admin, menu, dan slide awal
resources/
├── css/            # Tailwind CSS
├── js/             # komponen Alpine.js
└── views/          # Blade untuk halaman publik dan backoffice
```

## Pengembang

**Aditya Mukti Firmansyah**
SMK Wikrama 1 Jepara
