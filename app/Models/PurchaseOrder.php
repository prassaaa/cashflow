<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'po_number',
        'job_order_id',
        'supplier_name',
        'category',
        'description',
        'value',
        'po_date',
        'expected_delivery_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'po_date' => 'date',
            'expected_delivery_date' => 'date',
        ];
    }

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }
}
