<?php
namespace Database\Seeders;

use App\Models\Page;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update admin user
        User::updateOrCreate([
            'email' => 'admin@raissacatering.com',
        ], [
            'name'              => 'Admin Raissa',
            'password'          => Hash::make('admin123'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create or update demo customer
        User::updateOrCreate([
            'email' => 'customer@demo.com',
        ], [
            'name'              => 'Budi Santoso',
            'password'          => Hash::make('demo123'),
            'role'              => 'customer',
            'phone'             => '081234567890',
            'email_verified_at' => now(),
        ]);

        // Create or update About Us page content
        Page::updateOrCreate([
            'slug' => 'about',
        ], [
            'title'    => 'Solusi Catering Profesional untuk Setiap Acara',
            'subtitle' => 'Raissa Catering hadir sebagai partner catering terpercaya di Samarinda. Kami menyediakan berbagai paket nasi kotak dan catering prasmanan untuk pernikahan, ulang tahun, meeting kantor, syukuran, dan acara keluarga.',
            'body'     => "Raissa Catering menyediakan paket catering dengan pilihan menu yang fleksibel, dari nasi kotak standar sampai paket premium untuk tamu istimewa.\n\nKami juga melayani permintaan tambahan seperti minuman, dessert, dan kebutuhan khusus menu halal.\n\nSetiap pesanan didampingi dokumentasi pesanan, sehingga Anda bisa memantau status secara online.",
        ]);

        // Paket Acara 1 - Per Kotak Standar
        $paket1 = Package::updateOrCreate([
            'slug' => 'paket-acara-1',
        ], [
            'name'          => 'Paket Acara 1',
            'description'   => 'Paket nasi kotak premium untuk berbagai acara. Cocok untuk meeting, syukuran, dan acara keluarga. Setiap kotak sudah termasuk nasi, lauk pilihan, sayur, dan kerupuk.',
            'price_per_box' => 35000,
            'min_order'     => 20,
            'event_type'    => 'lainnya',
            'menu_items'    => [
                'Nasi Putih',
                'Ayam Goreng / Ayam Bakar (pilihan)',
                'Tempe Orek',
                'Sayur Buncis',
                'Sambal Goreng',
                'Kerupuk',
                'Buah Potong',
            ],
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $paket1->addons()->updateOrCreate(
            ['name' => 'Tambah Air Mineral 600ml'],
            ['price' => 4000]
        );
        $paket1->addons()->updateOrCreate(
            ['name' => 'Upgrade Ayam Bakar Special'],
            ['price' => 8000]
        );
        $paket1->addons()->updateOrCreate(
            ['name' => 'Tambah Puding'],
            ['price' => 5000]
        );

        // Paket Acara 2 - Premium
        $paket2 = Package::updateOrCreate([
            'slug' => 'paket-acara-2',
        ], [
            'name'          => 'Paket Acara 2',
            'description'   => 'Paket nasi kotak premium spesial untuk pernikahan dan acara resmi. Tampilan lebih mewah dengan box cantik, menu lengkap, dan kualitas bahan terbaik.',
            'price_per_box' => 55000,
            'min_order'     => 50,
            'event_type'    => 'pernikahan',
            'menu_items'    => [
                'Nasi Putih / Nasi Kuning (pilihan)',
                'Ayam Bakar Madu / Ayam Goreng Crispy',
                'Rendang Daging Sapi',
                'Perkedel Kentang',
                'Tumis Brokoli & Wortel',
                'Sambal Goreng Ati',
                'Acar',
                'Kerupuk Udang',
                'Buah Segar',
                'Teh Kotak / Air Mineral',
            ],
            'is_active'  => true,
            'sort_order' => 2,
        ]);

        $paket2->addons()->updateOrCreate(
            ['name' => 'Tambah Sup Soto Ayam'],
            ['price' => 10000]
        );
        $paket2->addons()->updateOrCreate(
            ['name' => 'Upgrade Box Premium Bertutup'],
            ['price' => 5000]
        );
        $paket2->addons()->updateOrCreate(
            ['name' => 'Tambah Kue Lapis 2 pcs'],
            ['price' => 8000]
        );
        $paket2->addons()->updateOrCreate(
            ['name' => 'Tambah Jus Buah'],
            ['price' => 10000]
        );

        // Paket Meeting
        Package::updateOrCreate([
            'slug' => 'paket-meeting',
        ], [
            'name'          => 'Paket Meeting',
            'description'   => 'Paket snack dan makan siang praktis untuk rapat kantor dan seminar. Termasuk snack pagi dan makan siang lengkap.',
            'price_per_box' => 45000,
            'min_order'     => 10,
            'event_type'    => 'meeting',
            'menu_items'    => [
                'Snack Pagi (Kue & Gorengan)',
                'Nasi Kotak Siang',
                'Lauk Pilihan (Ayam/Ikan)',
                'Air Mineral',
                'Teh / Kopi',
            ],
            'is_active'  => true,
            'sort_order' => 3,
        ]);

        // Call Custom Menu Seeder
        $this->call(CustomMenuSeeder::class);
    }
}
