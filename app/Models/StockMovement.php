<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'reason',
        'stock_before',
        'stock_after'
    ];

    public function casts(): array
    {
        return [
            'quantity' => 'integer',
            'stock_before' => 'integer',
            'stock_after' => 'integer',
        ];
    }

    // stockmovement belongs to one product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // stockmovement belongs to one user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // helper
    public function typeLabel(): string
    {
            return match($this->type) {
                'in' => 'Stock in',
                'out' => 'Stock out',
                'adjustment' => 'Adjustment',
                default => ucfirst($this->type),
            };
    }
}
