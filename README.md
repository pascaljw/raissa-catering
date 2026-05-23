# Raissa Catering

Sistem pemesanan katering online berbasis Laravel 13.

Project ini menyediakan:
- Halaman publik paket catering dan detail paket
- Form checkout untuk membuat pesanan
- Integrasi Xendit untuk pembayaran DP dan pelunasan online
- Panel admin untuk mengelola pesanan, paket, dan konten halaman statis (Tentang Kami)
- Akun demo untuk pengujian cepat

## Fitur Utama

- Customer dapat melihat paket, checkout, dan memantau status pesanan
- Admin dapat melihat daftar pesanan, mengubah status, dan konfirmasi pembayaran tunai
- Halaman `Tentang Kami` dapat diedit via dashboard admin
- Sistem pembayaran mendukung status `unpaid`, `dp_pending`, `dp_paid`, `full_pending`, dan `fully_paid`

## Quickstart

1. Clone repository:

```bash
git clone https://github.com/pascaljw/raissa-catering.git
cd raissa-catering
```

2. Install dependencies:

```bash
composer install
npm install
npm run build
```

3. Salin file environment:

```bash
copy .env.example .env
```

4. Buat app key:

```bash
php artisan key:generate
```

5. Atur koneksi database di `.env` lalu jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

6. Jalankan server lokal:

```bash
php artisan serve
```

## Akun Demo

- Admin
  - Email: `admin@raissacatering.com`
  - Password: `admin123`

- Customer
  - Email: `customer@demo.com`
  - Password: `demo123`

## Struktur Penting

- `app/Models/Order.php` — logika pesanan dan status
- `app/Services/XenditService.php` — integrasi Xendit invoice dan webhook
- `app/Http/Controllers/Customer/OrderController.php` — alur checkout dan pembayaran customer
- `app/Http/Controllers/Admin/OrderController.php` — manajemen pesanan admin
- `resources/views/customer` — tampilan untuk user
- `resources/views/admin` — tampilan untuk admin

## Catatan

- Pastikan `services.xendit.secret_key` dan `services.xendit.webhook_token` sudah diatur di `.env` jika menggunakan Xendit.
- Jika hanya ingin tes lokal tanpa Xendit, kamu bisa gunakan mode cash atau dummy invoice.

## Lisensi

Project ini menggunakan lisensi MIT.
