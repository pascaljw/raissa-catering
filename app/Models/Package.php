<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model
{
    protected $fillable = [
        'name','slug','description','price_per_box','min_order',
        'image','event_type','menu_items','is_active','sort_order',
    ];

    protected $casts = [
        'menu_items'   => 'array',
        'price_per_box'=> 'decimal:2',
        'is_active'    => 'boolean',
    ];

    public function addons(): HasMany  { return $this->hasMany(PackageAddon::class); }
    public function orders(): HasMany  { return $this->hasMany(Order::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }
    public function items(): BelongsToMany { return $this->belongsToMany(Item::class, 'item_package'); }

    public function getImageUrlAttribute(): string {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-package.jpg');
    }

    public function getEventTypeLabelAttribute(): string {
        return match($this->event_type) {
            'pernikahan'  => 'Pernikahan',
            'ulang_tahun' => 'Ulang Tahun',
            'meeting'     => 'Meeting / Rapat',
            'syukuran'    => 'Syukuran',
            default       => 'Lainnya',
        };
    }

    public function getAverageRatingAttribute(): float {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    public function scopeActive($query) {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string { return 'slug'; }
}
