<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Testimoni pelanggan
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating');  // 1-5
            $table->text('comment')->nullable();
            $table->string('event_type')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        // Tanggal yang tidak tersedia (diblokir admin)
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        // Notifikasi in-app
        Schema::create('notifications_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type');         // order_confirmed, payment_received, dll
            $table->string('link')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('notifications_log');
        Schema::dropIfExists('blocked_dates');
        Schema::dropIfExists('reviews');
    }
};
