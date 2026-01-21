<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'job_order_id',
        'invoice_number',
        'shipped_date',
        'shipper',
        'buyer',
        'po_number',
        'container',
        'amount',
        'deposit_discount',
        'paid_amount',
        'invoice_date',
        'due_date',
        'paid_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'deposit_discount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'invoice_date' => 'date',
            'shipped_date' => 'date',
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    // Calculated fields
    public function getBalanceAttribute(): float
    {
        return $this->amount - $this->deposit_discount;
    }

    public function getOutstandingAttribute(): float
    {
        return $this->balance - $this->paid_amount;
    }

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }
}
