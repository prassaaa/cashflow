<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManPower extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'job_order_id',
        'employee_id',
        'work_date',
        'hours_worked',
        'rate_per_hour',
        'total_cost',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'hours_worked' => 'decimal:2',
            'rate_per_hour' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
