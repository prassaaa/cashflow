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

class StatsOverviewWidget extends ChartWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Tren Keuangan 6 Bulan Terakhir';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '400px';

    public static function canView(): bool
    {
        // Only super_admin and accounting can see stats overview
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'accounting']);
    }

    protected function getData(): array
    {
        $months = collect();
        $joActiveData = collect();
        $poPendingData = collect();
        $unpaidInvoiceData = collect();
        $overdueInvoiceData = collect();
        $expenseData = collect();
        $salaryData = collect();

        // Get last 6 months data
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));

            // Total nilai JO aktif per bulan
            $joActive = JobOrder::whereIn('status', ['pending', 'in_progress'])
                ->whereMonth('order_date', $date->month)
                ->whereYear('order_date', $date->year)
                ->sum('value');
            $joActiveData->push($joActive);

            // Total PO pending per bulan
            $poPending = PurchaseOrder::whereIn('status', ['pending', 'approved'])
                ->whereMonth('po_date', $date->month)
                ->whereYear('po_date', $date->year)
                ->sum('value');
            $poPendingData->push($poPending);

            // Invoice belum dibayar per bulan
            $unpaidInvoice = Invoice::whereIn('status', ['draft', 'sent'])
                ->whereMonth('invoice_date', $date->month)
                ->whereYear('invoice_date', $date->year)
                ->sum('amount');
            $unpaidInvoiceData->push($unpaidInvoice);

            // Invoice jatuh tempo per bulan
            $overdueInvoice = Invoice::where('status', '!=', 'paid')
                ->where('due_date', '<', $date->endOfMonth())
                ->whereMonth('due_date', $date->month)
                ->whereYear('due_date', $date->year)
                ->sum('amount');
            $overdueInvoiceData->push($overdueInvoice);

            // Total expenses per bulan
            $monthlyExpenses = Expense::whereMonth('expense_date', $date->month)
                ->whereYear('expense_date', $date->year)
                ->sum('amount');
            $expenseData->push($monthlyExpenses);

            // Total gaji per bulan
            $monthlySalary = Salary::where('period', $date->format('Y-m'))
                ->sum('total');
            $salaryData->push($monthlySalary);
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
            'labels' => $months->toArray(),
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
