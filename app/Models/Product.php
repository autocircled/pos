<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'company',
        'sku',
        'barcode',
        'description',
        'cost_price',
        'selling_price',
        'quantity',
        'alert_quantity',
        'unit',
        'image',
        'is_active',
        'requires_custom_price',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer',
        'alert_quantity' => 'integer',
        'is_active' => 'boolean',
        'requires_custom_price' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function inventoryBatches()
    {
        return $this->hasMany(InventoryBatch::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->alert_quantity;
    }

    public function getProfit(): float
    {
        return $this->selling_price - $this->cost_price;
    }

    public static function generateSku(): string
    {
        $prefix = 'STN';
        $number = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        return $prefix . $number;
    }

    /**
     * Full URL for the product image (works for both public/uploads and storage/app/public paths).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        if (str_starts_with($this->image, 'uploads/')) {
            return asset($this->image);
        }
        return asset('storage/' . $this->image);
    }

    /**
     * Get FIFO cost price for this product
     */
    public function getFifoCostPrice(): float
    {
        return InventoryBatch::getAverageCostPrice($this->id);
    }

    /**
     * Get available quantity from FIFO batches
     */
    public function getFifoQuantity(): int
    {
        return InventoryBatch::getTotalAvailableQuantity($this->id);
    }

    /**
     * Get FIFO batches for this product
     */
    public function getFifoBatches()
    {
        return InventoryBatch::getFifoBatches($this->id);
    }

    /**
     * Create inventory batch from purchase
     */
    public function createInventoryBatch($quantity, $costPrice, $batchDate = null, $purchaseItemId = null, $notes = null)
    {
        return InventoryBatch::create([
            'product_id' => $this->id,
            'purchase_item_id' => $purchaseItemId,
            'cost_price' => $costPrice,
            'selling_price' => $this->selling_price, // Store current selling price
            'quantity_initial' => $quantity,
            'quantity_remaining' => $quantity,
            'batch_date' => $batchDate ?? now()->toDateString(),
            'notes' => $notes,
        ]);
    }

    /**
     * Reduce stock using FIFO method
     */
    public function reduceFifoStock($quantity): array
    {
        $batches = $this->getFifoBatches();
        $usedBatches = [];
        $remainingQuantity = $quantity;

        foreach ($batches as $batch) {
            if ($remainingQuantity <= 0) {
                break;
            }

            $availableQuantity = $batch->quantity_remaining;
            $useQuantity = min($remainingQuantity, $availableQuantity);

            if ($useQuantity > 0) {
                $batch->reduceStock($useQuantity);
                $usedBatches[] = [
                    'batch_id' => $batch->id,
                    'quantity' => $useQuantity,
                    'cost_price' => $batch->cost_price,
                ];
                $remainingQuantity -= $useQuantity;
            }
        }

        if ($remainingQuantity > 0) {
            throw new \Exception("Insufficient stock. Required: {$quantity}, Available: " . ($quantity - $remainingQuantity));
        }

        // Update product quantity
        $this->decrement('quantity', $quantity);

        return $usedBatches;
    }

    /**
     * Add stock back to FIFO batches (for returns/voids)
     */
    public function addFifoStock($quantity, $batchIds = []): bool
    {
        if (empty($batchIds)) {
            // If no specific batches provided, add to the oldest batches
            $batches = $this->getFifoBatches();
            $remainingQuantity = $quantity;

            foreach ($batches as $batch) {
                if ($remainingQuantity <= 0) {
                    break;
                }

                $addQuantity = min($remainingQuantity, $batch->quantity_initial - $batch->quantity_remaining);
                if ($addQuantity > 0) {
                    $batch->addStock($addQuantity);
                    $remainingQuantity -= $addQuantity;
                }
            }
        } else {
            // Add to specific batches
            foreach ($batchIds as $batchId => $batchQuantity) {
                $batch = InventoryBatch::find($batchId);
                if ($batch && $batch->product_id === $this->id) {
                    $batch->addStock($batchQuantity);
                }
            }
        }

        // Update product quantity
        $this->increment('quantity', $quantity);

        return true;
    }
}
