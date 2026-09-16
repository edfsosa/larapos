<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'amount_received',
        'change',
        'reference',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generateReference(): string
    {
        return 'S-' . now()->format('Ymd') . '-' . str_pad(
            (string) (static::whereDate('created_at', today())->count() + 1),
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}
