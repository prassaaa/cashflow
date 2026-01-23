<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrdAttendance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'status',
        'present_count',
        'absent_count',
        'deduction_count',
        'new_hires_count',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'present_count' => 'integer',
            'absent_count' => 'integer',
            'deduction_count' => 'integer',
            'new_hires_count' => 'integer',
        ];
    }
}
