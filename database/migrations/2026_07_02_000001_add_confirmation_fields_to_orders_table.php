<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_scheme', ['dp', 'full'])->nullable()->after('payment_status');
            $table->timestamp('confirmed_at')->nullable()->after('payment_scheme');
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->after('confirmed_at');
            $table->text('admin_confirmation_notes')->nullable()->after('confirmed_by');
        });
    }

    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['payment_scheme', 'confirmed_at', 'confirmed_by', 'admin_confirmation_notes']);
        });
    }
};
