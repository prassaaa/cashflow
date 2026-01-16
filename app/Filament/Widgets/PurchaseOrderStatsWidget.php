<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderStatsWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        // Purchasing and super_admin can see PO stats
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'purchasing']);
    }

    protected function getStats(): array
    {
        $currentMonth = Carbon::now();

        $totalPOThisMonth = PurchaseOrder::whereMonth('po_date', $currentMonth->month)
            ->whereYear('po_date', $currentMonth->year)
            ->count();

        $totalValueThisMonth = PurchaseOrder::whereMonth('po_date', $currentMonth->month)
            ->whereYear('po_date', $currentMonth->year)
            ->sum('value');

        $pendingPO = PurchaseOrder::where('status', 'pending')->count();
        $approvedPO = PurchaseOrder::where('status', 'approved')->count();
        $orderedPO = PurchaseOrder::where('status', 'ordered')->count();
        $receivedPO = PurchaseOrder::where('status', 'received')->count();

        $pendingValue = PurchaseOrder::where('status', 'pending')->sum('value');

        return [
            Stat::make('PO Bulan Ini', $totalPOThisMonth)
                ->description($currentMonth->translatedFormat('F Y'))
                ->color('primary')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Nilai PO Bulan Ini', 'Rp ' . number_format($totalValueThisMonth, 0, ',', '.'))
                ->description('Total nilai')
                ->color('warning')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('PO Pending', $pendingPO)
                ->description('Rp ' . number_format($pendingValue, 0, ',', '.'))
                ->color('danger')
                ->icon('heroicon-o-clock'),

            Stat::make('PO Approved', $approvedPO)
                ->description('Menunggu order')
                ->color('info')
                ->icon('heroicon-o-check-circle'),

            Stat::make('PO Ordered', $orderedPO)
                ->description('Sudah diorder')
                ->color('primary')
                ->icon('heroicon-o-truck'),

            Stat::make('PO Received', $receivedPO)
                ->description('Barang diterima')
                ->color('success')
                ->icon('heroicon-o-check-badge'),
        ];
    }
}
