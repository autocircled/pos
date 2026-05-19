<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MfsAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'account_number',
        'opening_balance',
        'current_balance',
        'is_active',
        'cash_in_rate',
        'cash_out_rate',
        'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'cash_in_rate' => 'decimal:3',
        'cash_out_rate' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(MfsTransaction::class)->orderBy('created_at', 'desc');
    }

    public function getTodayCashIn(): float
    {
        return $this->transactions()
            ->where('transaction_type', 'cash_in')
            ->whereDate('created_at', today())
            ->sum('amount');
    }

    public function getTodayCashOut(): float
    {
        return $this->transactions()
            ->where('transaction_type', 'cash_out')
            ->whereDate('created_at', today())
            ->sum('amount');
    }

    public function getTodayCommission(): float
    {
        return $this->transactions()
            ->whereDate('created_at', today())
            ->sum('commission_earned');
    }

    public function getTotalCommission(): float
    {
        return $this->transactions()->sum('commission_earned');
    }
}