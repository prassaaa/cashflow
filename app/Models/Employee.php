<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_number',
        'name',
        'type',
        'position',
        'department',
        'base_salary',
        'join_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'join_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function manPowers(): HasMany
    {
        return $this->hasMany(ManPower::class);
    }
}
