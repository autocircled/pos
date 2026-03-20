<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'address',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Total unpaid balance across all received purchases for this supplier.
     */
    public function totalDue(): float
    {
        return (float) $this->purchases()
            ->where('status', 'received')
            ->selectRaw('COALESCE(SUM(total - paid_amount), 0)')
            ->value('COALESCE(SUM(total - paid_amount), 0)');
    }
}
