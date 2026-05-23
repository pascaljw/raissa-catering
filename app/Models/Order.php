<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number','user_id','package_id','quantity',
        'price_per_box','subtotal','addon_total','total_amount',
        'dp_amount','remaining_amount','event_name','event_location',
        'event_address','event_date','delivery_time','notes','status',
        'payment_status','selected_addons','contact_name','contact_phone',
    ];

    protected $casts = [
        'event_date'      => 'date',
        'delivery_time'   => 'datetime:H:i',
        'selected_addons' => 'array',
        'total_amount'    => 'decimal:2',
        'dp_amount'       => 'decimal:2',
        'remaining_amount'=> 'decimal:2',
    ];

    // Relasi
    public function user(): BelongsTo    { return $this->belongsTo(User::class); }
    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
    public function payments(): HasMany  { return $this->hasMany(Payment::class); }
    public function review(): HasOne     { return $this->hasOne(Review::class); }

    // Helpers
    public function getDpPaymentAttribute()   { return $this->payments()->where('type','dp')->latest()->first(); }
    public function getFullPaymentAttribute() { return $this->payments()->where('type','full_payment')->latest()->first(); }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'pending'    => 'Menunggu DP',
            'dp_paid'    => 'DP Dibayar',
            'confirmed'  => 'Dikonfirmasi',
            'processing' => 'Diproses',
            'delivering' => 'Sedang Dikirim',
            'delivered'  => 'Sudah Sampai',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'pending'    => 'yellow',
            'dp_paid'    => 'blue',
            'confirmed'  => 'indigo',
            'processing' => 'purple',
            'delivering' => 'orange',
            'delivered'  => 'teal',
            'completed'  => 'green',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }

    public function canPay(): bool {
        return $this->payment_status === 'unpaid';
    }

    public function canPayFull(): bool {
        return $this->status === 'delivered' && $this->payment_status === 'dp_paid';
    }

    // Generate nomor order: RC-YYYYMMDD-0001
    public static function generateOrderNumber(): string {
        $date   = now()->format('Ymd');
        $prefix = "RC-{$date}-";
        $last   = self::where('order_number','like', $prefix.'%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
