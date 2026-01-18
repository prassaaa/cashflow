<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderStatsWidget extends ChartWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Status Purchase Order (6 Bulan Terakhir)';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '350px';

    public static function canView(): bool
    {
        // Purchasing and super_admin can see PO stats
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'purchasing']);
    }

    protected function getData(): array
    {
        $months = collect();
        $pendingData = collect();
        $approvedData = collect();
        $orderedData = collect();
        $receivedData = collect();

        // Get last 6 months data
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));

            // Count PO by status per month
            $pending = PurchaseOrder::where('status', 'pending')
                ->whereMonth('po_date', $date->month)
                ->whereYear('po_date', $date->year)
                ->sum('value');
            $pendingData->push($pending);

            $approved = PurchaseOrder::where('status', 'approved')
                ->whereMonth('po_date', $date->month)
                ->whereYear('po_date', $date->year)
                ->sum('value');
            $approvedData->push($approved);

            $ordered = PurchaseOrder::where('status', 'ordered')
                ->whereMonth('po_date', $date->month)
                ->whereYear('po_date', $date->year)
                ->sum('value');
            $orderedData->push($ordered);

            $received = PurchaseOrder::where('status', 'received')
                ->whereMonth('po_date', $date->month)
                ->whereYear('po_date', $date->year)
                ->sum('value');
            $receivedData->push($received);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pending',
                    'data' => $pendingData->toArray(),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Approved',
                    'data' => $approvedData->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Ordered',
                    'data' => $orderedData->toArray(),
                    'backgroundColor' => 'rgba(251, 146, 60, 0.8)',
                    'borderColor' => 'rgb(251, 146, 60)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Received',
                    'data' => $receivedData->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                ],
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
