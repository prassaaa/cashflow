<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\JobOrder;
use App\Models\PurchaseOrder;
use App\Models\Salary;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        // Only super_admin and accounting can see stats overview
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'accounting']);
    }

    protected function getStats(): array
    {
        // Total nilai JO aktif
        $totalJoValue = JobOrder::whereIn('status', ['pending', 'in_progress'])
            ->sum('value');

        // Total PO pending
        $totalPoValue = PurchaseOrder::whereIn('status', ['pending', 'approved'])
            ->sum('value');

        // Invoice belum dibayar
        $unpaidInvoice = Invoice::whereIn('status', ['draft', 'sent', 'partial'])
            ->sum('amount');

        // Invoice jatuh tempo (overdue)
        $overdueInvoice = Invoice::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->sum('amount');

        // Total expenses bulan ini
        $monthlyExpenses = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        // Total gaji bulan ini
        $monthlySalary = Salary::where('period', now()->format('Y-m'))
            ->sum('total');

        return [
            Stat::make('Total Job Order Aktif', 'Rp ' . number_format($totalJoValue, 0, ',', '.'))
                ->description('Nilai JO pending & in progress')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('primary'),

            Stat::make('Total PO Pending', 'Rp ' . number_format($totalPoValue, 0, ',', '.'))
                ->description('PO menunggu & disetujui')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('warning'),

            Stat::make('Invoice Belum Dibayar', 'Rp ' . number_format($unpaidInvoice, 0, ',', '.'))
                ->description('Draft, terkirim & sebagian')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),

            Stat::make('Invoice Jatuh Tempo', 'Rp ' . number_format($overdueInvoice, 0, ',', '.'))
                ->description('Overdue - perlu follow up')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($overdueInvoice > 0 ? 'danger' : 'success'),

            Stat::make('Pengeluaran Bulan Ini', 'Rp ' . number_format($monthlyExpenses, 0, ',', '.'))
                ->description('Total expenses ' . now()->format('F Y'))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('gray'),

            Stat::make('Gaji Bulan Ini', 'Rp ' . number_format($monthlySalary, 0, ',', '.'))
                ->description('Total salary ' . now()->format('Y-m'))
                ->descriptionIcon('heroicon-o-users')
                ->color('gray'),
        ];
    }
}
