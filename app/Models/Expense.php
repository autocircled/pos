<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public const CATEGORIES = [
        'Rent',
        'Utilities',
        'Salaries',
        'Supplies',
        'Transport',
        'Marketing',
        'Maintenance',
        'Other',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'amount',
        'expense_date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
