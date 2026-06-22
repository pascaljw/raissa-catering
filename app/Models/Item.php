<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category',
        'additional_price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'item_package');
    }

    public function orderLineItems(): HasMany
    {
        return $this->hasMany(OrderLineItem::class);
    }
}
