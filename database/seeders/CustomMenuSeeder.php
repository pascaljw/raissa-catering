<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Package;
use Illuminate\Database\Seeder;

class CustomMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Main Dishes / Protein Items
        $mainDishes = [
            ['name' => 'Ayam Goreng Crispy', 'category' => 'protein', 'additional_price' => 0],
            ['name' => 'Ayam Bakar Madu', 'category' => 'protein', 'additional_price' => 2000],
            ['name' => 'Ayam Bakar Bumbu Khas', 'category' => 'protein', 'additional_price' => 2000],
            ['name' => 'Daging Rendang', 'category' => 'protein', 'additional_price' => 5000],
            ['name' => 'Daging Sate', 'category' => 'protein', 'additional_price' => 4000],
            ['name' => 'Ikan Bakar', 'category' => 'protein', 'additional_price' => 3000],
            ['name' => 'Ikan Goreng', 'category' => 'protein', 'additional_price' => 2500],
            ['name' => 'Udang Goreng', 'category' => 'protein', 'additional_price' => 6000],
            ['name' => 'Cumi Goreng', 'category' => 'protein', 'additional_price' => 5000],
            ['name' => 'Telur Dadar', 'category' => 'protein', 'additional_price' => 1000],
        ];

        // Side Dishes / Vegetables
        $sideDishes = [
            ['name' => 'Perkedel Kentang', 'category' => 'vegetable', 'additional_price' => 0],
            ['name' => 'Tempe Goreng', 'category' => 'vegetable', 'additional_price' => 0],
            ['name' => 'Tempe Orek', 'category' => 'vegetable', 'additional_price' => 500],
            ['name' => 'Tahu Goreng', 'category' => 'vegetable', 'additional_price' => 0],
            ['name' => 'Tumis Brokoli', 'category' => 'vegetable', 'additional_price' => 1500],
            ['name' => 'Sayur Buncis Goreng', 'category' => 'vegetable', 'additional_price' => 1500],
            ['name' => 'Cabai Goreng', 'category' => 'vegetable', 'additional_price' => 1000],
            ['name' => 'Acar / Asinan', 'category' => 'vegetable', 'additional_price' => 0],
            ['name' => 'Lalapan Segar', 'category' => 'vegetable', 'additional_price' => 1000],
        ];

        // Soups / Curries
        $soups = [
            ['name' => 'Sambal Goreng Ati', 'category' => 'soup', 'additional_price' => 1000],
            ['name' => 'Sambal Goreng Telur Puyuh', 'category' => 'soup', 'additional_price' => 1500],
            ['name' => 'Kurma Ayam', 'category' => 'soup', 'additional_price' => 2000],
            ['name' => 'Lodeh Sayuran', 'category' => 'soup', 'additional_price' => 0],
            ['name' => 'Kuah Kental', 'category' => 'soup', 'additional_price' => 1000],
        ];

        // Condiments
        $condiments = [
            ['name' => 'Sambal Matah', 'category' => 'condiment', 'additional_price' => 0],
            ['name' => 'Sambal Kencur', 'category' => 'condiment', 'additional_price' => 0],
            ['name' => 'Kecap Manis', 'category' => 'condiment', 'additional_price' => 0],
            ['name' => 'Kerupuk', 'category' => 'condiment', 'additional_price' => 0],
        ];

        // Desserts & Fruits
        $desserts = [
            ['name' => 'Buah Potong Segar', 'category' => 'dessert', 'additional_price' => 2000],
            ['name' => 'Puding Rasa Buah', 'category' => 'dessert', 'additional_price' => 2500],
            ['name' => 'Kue Lapis', 'category' => 'dessert', 'additional_price' => 1500],
            ['name' => 'Brownies Homemade', 'category' => 'dessert', 'additional_price' => 2000],
            ['name' => 'Onde-onde', 'category' => 'dessert', 'additional_price' => 1500],
        ];

        // Beverages
        $beverages = [
            ['name' => 'Air Mineral 600ml', 'category' => 'beverage', 'additional_price' => 2000],
            ['name' => 'Teh Kotak', 'category' => 'beverage', 'additional_price' => 2000],
            ['name' => 'Jus Segar', 'category' => 'beverage', 'additional_price' => 3000],
        ];

        // Combine all items
        $allItems = array_merge($mainDishes, $sideDishes, $soups, $condiments, $desserts, $beverages);

        // Create all items
        $createdItems = [];
        foreach ($allItems as $item) {
            $createdItem = Item::updateOrCreate([
                'name' => $item['name'],
            ], [
                'category' => $item['category'],
                'additional_price' => $item['additional_price'],
                'description' => 'Menu item untuk paket custom',
                'is_active' => true,
            ]);
            $createdItems[] = $createdItem;
        }

        // Create Custom Menu Package
        $customPackage = Package::updateOrCreate([
            'slug' => 'paket-menu-custom',
        ], [
            'name' => 'Paket Menu Custom',
            'description' => 'Buat paket catering impian Anda! Pilih sendiri menu utama, lauk pendamping, sayur, sambal, dan hidangan penutup sesuai dengan preferensi Anda.',
            'price_per_box' => 40000,
            'min_order' => 10,
            'event_type' => 'lainnya',
            'menu_items' => [
                'Nasi Putih',
                'Pilih 1 Menu Utama',
                'Pilih 1 Lauk Pendamping',
                'Pilih 1 Sambal/Kuah',
                'Kerupuk & Acar',
                'Buah Segar atau Dessert',
            ],
            'is_active' => true,
            'sort_order' => 10,
        ]);

        // Attach some items to custom package (optional)
        $customPackage->items()->sync(
            array_slice(array_map(fn($item) => $item->id, $createdItems), 0, 15)
        );

        // Add addons for custom package
        $customPackage->addons()->updateOrCreate(
            ['name' => 'Upgrade Protein Premium'],
            ['price' => 8000]
        );
        $customPackage->addons()->updateOrCreate(
            ['name' => 'Tambah 1 Lauk Pendamping'],
            ['price' => 3000]
        );
        $customPackage->addons()->updateOrCreate(
            ['name' => 'Tambah Minuman'],
            ['price' => 2000]
        );
        $customPackage->addons()->updateOrCreate(
            ['name' => 'Upgrade Dessert Premium'],
            ['price' => 3000]
        );
    }
}
