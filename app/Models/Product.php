<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'barcode',
        'description',
        'unit_price',
        'stock_quantity',
        'low_stock_threshold',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }

    // a product belongs to ONE category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // product can have many stockMovements
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // helper
    public function isLowStock(): bool
    {
        return $this->stock_quantity > 0
            && $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    // readable stock status
    public function stockStatus(): string
    {
        if ($this->isOutOfStock()){
            return 'out_of_stock';
        }

        if ($this->isLowStock()) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    
}
