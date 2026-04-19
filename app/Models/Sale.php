<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'change_amount',
        'due_amount',
        'payment_status',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateInvoiceNumber(?Carbon $saleDate = null): string
    {
        $saleDate = $saleDate ?? now();
        $prefix = 'INV';
        $date = $saleDate->format('Ymd');
        $count = self::whereDate('created_at', $saleDate->toDateString())->count() + 1;
        return $prefix . $date . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function getProfit(): float
    {
        return $this->items->sum(function ($item) {
            return ($item->getActualPrice() - $item->cost_price) * $item->quantity - $item->discount;
        });
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'paid' => '<span class="badge bg-success">Paid</span>',
            'partial' => '<span class="badge bg-warning">Partial</span>',
            'due' => '<span class="badge bg-danger">Due</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }

    public function hasDue(): bool
    {
        return $this->due_amount > 0;
    }

    public function getRemainingAmount(): float
    {
        return max(0, $this->total - $this->paid_amount);
    }

    public function duePayments()
    {
        return $this->hasMany(DuePayment::class);
    }

    public function getTotalPaidAmount(): float
    {
        return $this->paid_amount + $this->duePayments()->sum('amount');
    }

    public function getRemainingDue(): float
    {
        return max(0, $this->due_amount - $this->duePayments()->sum('amount'));
    }

    public function isFullyPaid(): bool
    {
        return $this->getRemainingDue() <= 0;
    }
}
