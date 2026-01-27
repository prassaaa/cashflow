<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\OtherCost;
use App\Models\PurchaseOrder;
use App\Models\Salary;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CashFlowChartWidget extends ChartWidget
{
    protected ?string $heading = 'Cash Flow 6 Bulan Terakhir';

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    public static function canView(): bool
    {
        // Only super_admin and accounting can see cash flow chart
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'accounting']);
    }

    protected function getData(): array
    {
        $months = collect();
        $incomeData = collect();
        $expenseData = collect();

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));

            // Income = Invoice paid
            $income = Invoice::where('status', 'paid')
                ->whereMonth('paid_date', $date->month)
                ->whereYear('paid_date', $date->year)
                ->sum('amount');
            $incomeData->push($income);

            // Expenses = PO + Expense + Salary + OtherCost
            $po = PurchaseOrder::whereIn('status', ['approved', 'received'])
                ->whereMonth('po_date', $date->month)
                ->whereYear('po_date', $date->year)
                ->sum('value');

            $expense = Expense::whereIn('status', ['approved', 'paid'])
                ->whereMonth('expense_date', $date->month)
                ->whereYear('expense_date', $date->year)
                ->sum('amount');

            $salary = Salary::where('status', 'paid')
                ->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('total');

            $otherCost = OtherCost::whereIn('status', ['approved', 'paid'])
                ->whereMonth('cost_date', $date->month)
                ->whereYear('cost_date', $date->year)
                ->sum('amount');

            $expenseData->push($po + $expense + $salary + $otherCost);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan (Invoice)',
                    'data' => $incomeData->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $expenseData->toArray(),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
