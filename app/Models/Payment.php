<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id','xendit_invoice_id','xendit_payment_id','payment_reference',
        'type','amount','method','status','proof_image','paid_at',
        'xendit_response','admin_notes',
    ];

    protected $casts = [
        'xendit_response' => 'array',
        'paid_at'         => 'datetime',
        'amount'          => 'decimal:2',
    ];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }

    public function getTypeLabelAttribute(): string {
        return $this->type === 'dp' ? 'Uang Muka (DP 50%)' : 'Pelunasan';
    }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'pending'  => 'Menunggu Pembayaran',
            'paid'     => 'Lunas',
            'failed'   => 'Gagal',
            'expired'  => 'Kadaluarsa',
            'refunded' => 'Dikembalikan',
            default    => $this->status,
        };
    }

    public static function generateReference(): string {
        return 'RC-PAY-' . strtoupper(uniqid());
    }
}
