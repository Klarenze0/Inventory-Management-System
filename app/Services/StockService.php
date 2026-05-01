<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\StockMovement;
use Exception;


class StockService
{
    public function recordMovement(
        Product $product,
        string $type,
        int $quantity,
        ?string $reason = null
    ): StockMovement {
        $this->validateMovement($product, $type, $quantity);

        $stockBefore = $product->stock_quantity;

        $stockAfter = $this->calculateNewStock(
            $stockBefore,
            $type,
            $quantity,
        );

        $product->update(['stock_quantity' => $stockAfter]);

        $movement = StockMovement::create([
            'product_id'        => $product->id,
            'user_id'           => auth()->id(),
            'type'              => $type,
            'quantity'          => $quantity,
            'reason'            => $reason,
            'stock_before'      => $stockBefore,
            'stock_after'       => $stockAfter,
        ]);

        ActivityLog::create([
            'user_id'       => auth()->id(),
            'action'        => "stock_{$type}",
            'description'   => $this->buildLogDescription(
                                    $product,
                                    $type,
                                    $quantity,
                                    $stockBefore,
                                    $stockAfter,
                                ),
            'subject_type'  => 'Product',
            'subject_id'    => $product->id,
        ]);

        return $movement;
    }

    private function validateMovement(
        Product $product,
        string $type,
        int $quantity
    ): void
    {
        if ($quantity <= 0) {
            throw new Exception(
                "Insufficient stock. Current stock: {$product->stock_quantity}" .
                "Requested: {$quantity}"
            );
        }

        if ($type === 'adjustment') {
            $newstock = $product->stock_quantity + $quantity;

            if ($newstock < 0) {
                throw new Exception(
                    "Adjustment would result in negative stock."
                );
            }
        }
    }

    private function calculateNewStock(
        int $currentStock,
        string $type,
        int $quantity
    ): int
    {
        return match($type) {
            'in'            => $currentStock + $quantity,
            'out'           => $currentStock - $quantity,
            'adjustment'    => $currentStock + $quantity,
            default         => throw new Exception("Invalid movement type: {$type}")
        };
    }

    private function buildLogDescription(
        Product $product,
        string  $type,
        int     $quantity,
        int     $stockBefore,
        int     $stockAfter
    ): string
    {
        $typeLabel = match($type) {
            'in'            => 'Stock in',
            'out'           => 'Stock out',
            'adjustment'    => 'Adjustment',
            default         => ucfirst($type) 
        };

        return "{$typeLabel} of {$quantity} units for product " .
               "\"{$product->name}\" (SKU: {$product->sku}). " .
               "Stock changed from {$stockBefore} to {$stockAfter}.";
    }
}
