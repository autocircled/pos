<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'purchase_item_id',
        'cost_price',
        'selling_price',
        'quantity_initial',
        'quantity_remaining',
        'batch_date',
        'notes',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity_initial' => 'integer',
        'quantity_remaining' => 'integer',
        'batch_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class);
    }

    /**
     * Get available quantity for this batch
     */
    public function getAvailableQuantity(): int
    {
        return $this->quantity_remaining;
    }

    /**
     * Check if this batch has available stock
     */
    public function hasStock(): bool
    {
        return $this->quantity_remaining > 0;
    }

    /**
     * Reduce stock from this batch
     */
    public function reduceStock(int $quantity): bool
    {
        if ($quantity > $this->quantity_remaining) {
            return false;
        }

        $this->quantity_remaining -= $quantity;
        return $this->save();
    }

    /**
     * Add stock back to this batch (for returns/voids)
     */
    public function addStock(int $quantity): bool
    {
        $this->quantity_remaining += $quantity;
        return $this->save();
    }

    /**
     * Get FIFO batches for a product (oldest first)
     */
    public static function getFifoBatches($productId)
    {
        return self::where('product_id', $productId)
            ->where('quantity_remaining', '>', 0)
            ->orderBy('batch_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get total available quantity for a product
     */
    public static function getTotalAvailableQuantity($productId): int
    {
        return self::where('product_id', $productId)
            ->where('quantity_remaining', '>', 0)
            ->sum('quantity_remaining');
    }

    /**
     * Get average cost price for a product
     */
    public static function getAverageCostPrice($productId): float
    {
        $batches = self::where('product_id', $productId)
            ->where('quantity_remaining', '>', 0)
            ->get();

        if ($batches->isEmpty()) {
            return 0;
        }

        $totalCost = $batches->sum(function ($batch) {
            return $batch->cost_price * $batch->quantity_remaining;
        });

        $totalQuantity = $batches->sum('quantity_remaining');

        return $totalQuantity > 0 ? $totalCost / $totalQuantity : 0;
    }
}
