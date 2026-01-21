<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\JobOrder;
use App\Models\PurchaseOrder;
use App\Models\Salary;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWeeklyWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Tren Keuangan Bulan Ini (Per Minggu)';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '400px';

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

            // Make sure we don't go past the end of month
            if ($weekEnd->gt($endOfMonth)) {
                $weekEnd = $endOfMonth->copy();
            }

            // For the 4th week, extend to end of month
            if ($i === 4) {
                $weekEnd = $endOfMonth->copy();
            }

            $weeks[] = [
                'label' => "Minggu $i",
                'start' => $weekStart->copy(),
                'end' => $weekEnd->copy(),
            ];

            $weekStart = $weekEnd->copy()->addDay();

            // If we've passed end of month, stop
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
        $joActiveData = collect();
        $poPendingData = collect();
        $unpaidInvoiceData = collect();
        $overdueInvoiceData = collect();
        $expenseData = collect();
        $salaryData = collect();

        $currentPeriod = Carbon::now()->format('Y-m');

        foreach ($weeks as $week) {
            $labels->push($week['label']);
            $start = $week['start'];
            $end = $week['end'];

            // Total nilai JO aktif per minggu
            $joActive = JobOrder::whereIn('status', ['pending', 'in_progress'])
                ->whereBetween('order_date', [$start, $end])
                ->sum('value');
            $joActiveData->push($joActive);

            // Total PO pending per minggu
            $poPending = PurchaseOrder::whereIn('status', ['pending', 'approved'])
                ->whereBetween('po_date', [$start, $end])
                ->sum('value');
            $poPendingData->push($poPending);

            // Invoice belum dibayar per minggu
            $unpaidInvoice = Invoice::whereIn('status', ['draft', 'sent'])
                ->whereBetween('invoice_date', [$start, $end])
                ->sum('amount');
            $unpaidInvoiceData->push($unpaidInvoice);

            // Invoice jatuh tempo per minggu
            $overdueInvoice = Invoice::where('status', '!=', 'paid')
                ->where('due_date', '<', Carbon::now())
                ->whereBetween('due_date', [$start, $end])
                ->sum('amount');
            $overdueInvoiceData->push($overdueInvoice);

            // Total expenses per minggu
            $monthlyExpenses = Expense::whereBetween('expense_date', [$start, $end])
                ->sum('amount');
            $expenseData->push($monthlyExpenses);

            // Total gaji - untuk salary kita gunakan period bulan ini
            // karena salary biasanya tidak per minggu tapi per bulan
            // Kita bagi rata atau tampilkan di minggu terakhir
            if ($week === end($weeks)) {
                $monthlySalary = Salary::where('period', $currentPeriod)->sum('total');
                $salaryData->push($monthlySalary);
            } else {
                $salaryData->push(0);
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Job Order Aktif',
                    'data' => $joActiveData->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                    'fill' => false,
                ],
                [
                    'label' => 'PO Pending',
                    'data' => $poPendingData->toArray(),
                    'borderColor' => 'rgb(251, 146, 60)',
                    'backgroundColor' => 'rgba(251, 146, 60, 0.1)',
                    'tension' => 0.4,
                    'fill' => false,
                ],
                [
                    'label' => 'Invoice Belum Dibayar',
                    'data' => $unpaidInvoiceData->toArray(),
                    'borderColor' => 'rgb(14, 165, 233)',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.1)',
                    'tension' => 0.4,
                    'fill' => false,
                ],
                [
                    'label' => 'Invoice Jatuh Tempo',
                    'data' => $overdueInvoiceData->toArray(),
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'tension' => 0.4,
                    'fill' => false,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $expenseData->toArray(),
                    'borderColor' => 'rgb(107, 114, 128)',
                    'backgroundColor' => 'rgba(107, 114, 128, 0.1)',
                    'tension' => 0.4,
                    'fill' => false,
                ],
                [
                    'label' => 'Gaji',
                    'data' => $salaryData->toArray(),
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.4,
                    'fill' => false,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'interaction' => [
                'mode' => 'nearest',
                'axis' => 'x',
                'intersect' => false,
            ],
        ];
    }

    protected function getFormattedState(string | int | float | null $state): string
    {
        if ($state === null) {
            return 'Rp 0';
        }
        return 'Rp ' . number_format($state, 0, ',', '.');
    }
}
