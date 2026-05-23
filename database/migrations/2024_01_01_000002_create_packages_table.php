<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // "Paket Acara 1", "Paket Acara 2"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_per_box', 10, 2);       // Harga per kotak
            $table->integer('min_order');                   // Minimum order kotak
            $table->string('image')->nullable();
            $table->enum('event_type', ['pernikahan','ulang_tahun','meeting','syukuran','lainnya'])->default('lainnya');
            $table->json('menu_items')->nullable();         // Daftar menu dalam paket
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('package_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('name');                         // "Tambah minuman", "Ganti lauk"
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('package_addons');
        Schema::dropIfExists('packages');
    }
};
