<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();       // RC-20240101-0001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained();
            $table->integer('quantity');                    // Jumlah kotak
            $table->decimal('price_per_box', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('addon_total', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);        // Total keseluruhan
            $table->decimal('dp_amount', 12, 2);           // 50% dari total
            $table->decimal('remaining_amount', 12, 2);    // Sisa 50%

            // Detail acara
            $table->string('event_name');
            $table->string('event_location');
            $table->text('event_address');
            $table->date('event_date');
            $table->time('delivery_time');
            $table->text('notes')->nullable();

            // Status pesanan
            $table->enum('status', [
                'pending',          // Menunggu DP
                'dp_paid',          // DP sudah dibayar
                'confirmed',        // Dikonfirmasi admin
                'processing',       // Sedang diproses/dimasak
                'delivering',       // Sedang dikirim
                'delivered',        // Sudah sampai
                'completed',        // Lunas & selesai
                'cancelled'         // Dibatalkan
            ])->default('pending');

            // Status pembayaran
            $table->enum('payment_status', [
                'unpaid',           // Belum bayar DP
                'dp_pending',       // DP menunggu konfirmasi
                'dp_paid',          // DP terkonfirmasi
                'full_pending',     // Pelunasan menunggu konfirmasi
                'fully_paid'        // Lunas
            ])->default('unpaid');

            $table->json('selected_addons')->nullable();
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
