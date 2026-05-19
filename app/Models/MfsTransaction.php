<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MfsTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'mfs_account_id',
        'transaction_type',
        'amount',
        'commission_rate',
        'commission_earned',
        'transaction_id',
        'customer_phone',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_earned' => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(MfsAccount::class, 'mfs_account_id');
    }

    protected static function booted(): void
    {
        static::creating(function ($transaction) {
            $account = MfsAccount::find($transaction->mfs_account_id);

            if ($transaction->transaction_type === 'cash_out') {
                $commissionRate = $account ? $account->cash_out_rate : 0.4;
            } else {
                $commissionRate = $account ? $account->cash_in_rate : 0.375;
            }

            $transaction->commission_rate = $commissionRate;
            $transaction->commission_earned = ($transaction->amount * $commissionRate) / 100;
        });

        static::created(function ($transaction) {
            $account = MfsAccount::find($transaction->mfs_account_id);
            $amount = $transaction->amount;
            $commission = $transaction->commission_earned;

            if ($transaction->transaction_type === 'cash_in') {
                $account->current_balance -= $amount;
                $account->current_balance += $commission;
            } else {
                $account->current_balance += $amount;
                $account->current_balance += $commission;
            }
            $account->save();
        });
    }
}