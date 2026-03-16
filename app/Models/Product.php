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
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer',
        'alert_quantity' => 'integer',
        'is_active' => 'boolean',
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
}
