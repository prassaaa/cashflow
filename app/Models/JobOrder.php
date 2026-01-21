<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'jo_number',
        'customer_name',
        'pic',
        'project_name',
        'description',
        'container_name',
        'quantity',
        'unit',
        'value',
        'order_date',
        'due_date',
        'status',
        'pipa_status',
        'carton_type',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'order_date' => 'date',
            'due_date' => 'date',
            'quantity' => 'integer',
        ];
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function manPowers(): HasMany
    {
        return $this->hasMany(ManPower::class);
    }

    public function productionProgress(): HasMany
    {
        return $this->hasMany(ProductionProgress::class);
    }

    public function otherCosts(): HasMany
    {
        return $this->hasMany(OtherCost::class);
    }
}
