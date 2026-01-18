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

class CashFlowChartWeeklyWidget extends ChartWidget
{
    protected ?string $heading = 'Cash Flow Bulan Ini (Per Minggu)';

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'accounting']);
    }

    protected function getWeekRanges(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $weeks = [];
        $weekStart = $startOfMonth->copy();

        for ($i = 1; $i <= 4; $i++) {
            $weekEnd = $weekStart->copy()->addDays(6);

            if ($weekEnd->gt($endOfMonth)) {
                $weekEnd = $endOfMonth->copy();
            }

            if ($i === 4) {
                $weekEnd = $endOfMonth->copy();
            }

            $weeks[] = [
                'label' => "Minggu $i",
                'start' => $weekStart->copy(),
                'end' => $weekEnd->copy(),
            ];

            $weekStart = $weekEnd->copy()->addDay();

            if ($weekStart->gt($endOfMonth)) {
                break;
            }
        }

        return $weeks;
    }

    protected function getData(): array
    {
        $weeks = $this->getWeekRanges();
        $labels = collect();
        $incomeData = collect();
        $expenseData = collect();

        foreach ($weeks as $week) {
            $labels->push($week['label']);
            $start = $week['start'];
            $end = $week['end'];

            // Income = Invoice paid
            $income = Invoice::where('status', 'paid')
                ->whereBetween('paid_date', [$start, $end])
                ->sum('amount');
            $incomeData->push($income);

            // Expenses = PO + Expense + Salary + OtherCost
            $po = PurchaseOrder::whereIn('status', ['approved', 'received'])
                ->whereBetween('po_date', [$start, $end])
                ->sum('value');

            $expense = Expense::whereIn('status', ['approved', 'paid'])
                ->whereBetween('expense_date', [$start, $end])
                ->sum('amount');

            $salary = Salary::where('status', 'paid')
                ->whereBetween('payment_date', [$start, $end])
                ->sum('total');

            $otherCost = OtherCost::whereIn('status', ['approved', 'paid'])
                ->whereBetween('cost_date', [$start, $end])
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
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
