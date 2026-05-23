<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('xendit_invoice_id')->nullable()->unique(); // ID dari Xendit
            $table->string('xendit_payment_id')->nullable();
            $table->string('payment_reference')->unique();  // Internal reference RC-PAY-XXXX
            $table->enum('type', ['dp', 'full_payment']);   // DP atau pelunasan
            $table->decimal('amount', 12, 2);
            $table->enum('method', [
                'xendit_va',        // Virtual Account
                'xendit_ewallet',   // GoPay, OVO, Dana
                'xendit_qris',      // QRIS
                'cash',             // Tunai saat delivery
                'manual_transfer'   // Transfer manual
            ])->nullable();
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired',
                'refunded'
            ])->default('pending');
            $table->string('proof_image')->nullable();      // Bukti bayar manual
            $table->timestamp('paid_at')->nullable();
            $table->json('xendit_response')->nullable();    // Raw response dari Xendit
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('confirmed_by')->nullable()->constrained('users');
            $table->enum('action', ['approve', 'reject']);
            $table->text('notes')->nullable();
            $table->timestamp('confirmed_at');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('payment_confirmations');
        Schema::dropIfExists('payments');
    }
};
