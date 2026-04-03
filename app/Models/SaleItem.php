<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'unit_price',
        'custom_price',
        'cost_price',
        'quantity',
        'discount',
        'total',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'custom_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'quantity' => 'integer',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getProfit(): float
    {
        $sellingPrice = $this->getActualPrice();
        return ($sellingPrice - $this->cost_price) * $this->quantity - $this->discount;
    }

    /**
     * Get the actual selling price (custom price or unit price)
     */
    public function getActualPrice(): float
    {
        return $this->custom_price ?? $this->unit_price;
    }

    /**
     * Check if this item has a custom price
     */
    public function hasCustomPrice(): bool
    {
        return !is_null($this->custom_price);
    }
}
