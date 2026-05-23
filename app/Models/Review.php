<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['user_id','order_id','package_id','rating','comment','event_type','is_approved'];
    protected $casts = ['is_approved' => 'boolean'];

    public function user(): BelongsTo    { return $this->belongsTo(User::class); }
    public function order(): BelongsTo   { return $this->belongsTo(Order::class); }
    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
}
