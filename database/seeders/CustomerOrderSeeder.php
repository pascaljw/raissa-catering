<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class CustomerOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $samarindaData = [
            "Loa Janan Ilir" => ["Harapan Baru", "Rapak Dalam", "Sengkotek", "Simpang Tiga", "Tani Aman"],
            "Palaran" => ["Bantuas", "Bukuan", "Handil Bakti", "Rawa Makmur", "Simpang Pasir"],
            "Samarinda Ilir" => ["Pelita", "Selili", "Sidodamai", "Sidomulyo", "Sungai Dama"],
            "Samarinda Kota" => ["Bugis", "Karang Mumus", "Pasar Pagi", "Pelabuhan", "Sungai Pinang Luar"],
            "Samarinda Seberang" => ["Baqa", "Gunung Panjang", "Mangkupalas", "Mesjid", "Sungai Keledang", "Tenun"],
            "Samarinda Ulu" => ["Air Hitam", "Air Putih", "Bukit Pinang", "Dadi Mulya", "Gunung Kelua", "Jawa", "Sidodadi", "Teluk Lerong Ilir"],
            "Samarinda Utara" => ["Budaya Pampang", "Lempake", "Sempaja Barat", "Sempaja Selatan", "Sempaja Timur", "Sempaja Utara", "Sungai Siring", "Tanah Merah"],
            "Sambutan" => ["Makroman", "Pulau Atas", "Sambutan", "Sindang Sari", "Sungai Kapih"],
            "Sungai Kunjang" => ["Loa Buah", "Loa Bakung", "Karang Anyar", "Karang Asam Ilir", "Karang Asam Ulu", "Teluk Lerong Ulu", "Lok Bahu"],
            "Sungai Pinang" => ["Bandara", "Gunung Lingai", "Mugirejo", "Sungai Pinang Dalam", "Temindung Permai"]
        ];

        $packages = Package::all();
        if ($packages->isEmpty()) {
            $this->command->info('No packages found. Please run DatabaseSeeder first.');
            return;
        }

        $kecamatans = array_keys($samarindaData);

        // Create 5 new customers
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => $faker->phoneNumber,
                'email_verified_at' => now(),
            ]);

            // Create 10-15 orders for each customer
            $numOrders = rand(10, 15);
            for ($j = 0; $j < $numOrders; $j++) {
                $package = $packages->random();
                $quantity = rand($package->min_order, $package->min_order + 50);
                $subtotal = $package->price_per_box * $quantity;
                $total = $subtotal;
                $dpAmount = $total * 0.5;
                $remaining = $total - $dpAmount;
                
                $kecamatan = $faker->randomElement($kecamatans);
                $kelurahan = $faker->randomElement($samarindaData[$kecamatan]);

                $statuses = ['pending', 'dp_paid', 'confirmed', 'processing', 'delivering', 'delivered', 'completed', 'cancelled'];
                $status = $faker->randomElement($statuses);

                $paymentStatus = 'unpaid';
                if (in_array($status, ['dp_paid', 'confirmed', 'processing', 'delivering'])) {
                    $paymentStatus = 'dp_paid';
                } elseif (in_array($status, ['delivered', 'completed'])) {
                    $paymentStatus = 'fully_paid';
                } elseif ($status == 'cancelled') {
                    $paymentStatus = 'unpaid';
                }

                Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'quantity' => $quantity,
                    'price_per_box' => $package->price_per_box,
                    'subtotal' => $subtotal,
                    'addon_total' => 0,
                    'total_amount' => $total,
                    'dp_amount' => $dpAmount,
                    'remaining_amount' => $remaining,
                    'event_name' => $faker->sentence(3),
                    'event_location' => 'Gedung ' . $faker->company,
                    'event_address' => $faker->address,
                    'kecamatan' => $kecamatan,
                    'kelurahan' => $kelurahan,
                    'event_date' => $faker->dateTimeBetween('-1 year', '+1 month')->format('Y-m-d'),
                    'delivery_time' => $faker->time('H:i'),
                    'contact_name' => $user->name,
                    'contact_phone' => $user->phone,
                    'notes' => $faker->optional()->sentence,
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                ]);
            }
        }
        
        $this->command->info('Customers and their orders seeded successfully.');
    }
}
