<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionProgress extends Model
{
    use SoftDeletes;

    protected $table = 'production_progress';

    protected $fillable = [
        'job_order_id',
        'report_date',
        'progress_percentage',
        'stage',
        'material',
        'packing',
        'description',
        'issues',
        'solution',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'progress_percentage' => 'decimal:2',
        ];
    }

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }
}
