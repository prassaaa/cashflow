<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\OtherCost;
use App\Models\PurchaseOrder;
use App\Models\Salary;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ExpenseBreakdownChart extends ChartWidget
{
    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 1;

    protected ?string $heading = 'Breakdown Pengeluaran Bulan Ini';

    protected ?string $maxHeight = '300px';

    public static function canView(): bool
    {
        // Only super_admin and accounting can see expense breakdown
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'accounting']);
    }

    protected function getData(): array
    {
        $month = Carbon::now();

        $po = PurchaseOrder::whereMonth('po_date', $month->month)
            ->whereYear('po_date', $month->year)
            ->sum('value');

        $expense = Expense::whereMonth('expense_date', $month->month)
            ->whereYear('expense_date', $month->year)
            ->sum('amount');

        $salary = Salary::where('period', $month->format('Y-m'))
            ->sum('total');

        $otherCost = OtherCost::whereMonth('cost_date', $month->month)
            ->whereYear('cost_date', $month->year)
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'data' => [$po, $expense, $salary, $otherCost],
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',   // blue - PO
                        'rgb(249, 115, 22)',   // orange - Expense
                        'rgb(34, 197, 94)',    // green - Salary
                        'rgb(168, 85, 247)',   // purple - Other
                    ],
                ],
            ],
            'labels' => ['Purchase Order', 'Expense', 'Gaji', 'Biaya Lainnya'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
