<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LatestPurchaseOrdersWidget extends ChartWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Purchase Order per Status (Bulan Ini)';

    protected ?string $maxHeight = '300px';

    public static function canView(): bool
    {
        // Purchasing and super_admin can see latest POs
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'purchasing']);
    }

    protected function getData(): array
    {
        $currentMonth = Carbon::now();

        // Get PO count and value by status this month
        $pending = PurchaseOrder::where('status', 'pending')
            ->whereMonth('po_date', $currentMonth->month)
            ->whereYear('po_date', $currentMonth->year)
            ->sum('value');

        $approved = PurchaseOrder::where('status', 'approved')
            ->whereMonth('po_date', $currentMonth->month)
            ->whereYear('po_date', $currentMonth->year)
            ->sum('value');

        $ordered = PurchaseOrder::where('status', 'ordered')
            ->whereMonth('po_date', $currentMonth->month)
            ->whereYear('po_date', $currentMonth->year)
            ->sum('value');

        $received = PurchaseOrder::where('status', 'received')
            ->whereMonth('po_date', $currentMonth->month)
            ->whereYear('po_date', $currentMonth->year)
            ->sum('value');

        return [
            'datasets' => [
                [
                    'label' => 'Nilai PO',
                    'data' => [$pending, $approved, $ordered, $received],
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.8)',    // red - Pending
                        'rgba(59, 130, 246, 0.8)',   // blue - Approved
                        'rgba(251, 146, 60, 0.8)',   // orange - Ordered
                        'rgba(34, 197, 94, 0.8)',    // green - Received
                    ],
                    'borderColor' => [
                        'rgb(239, 68, 68)',
                        'rgb(59, 130, 246)',
                        'rgb(251, 146, 60)',
                        'rgb(34, 197, 94)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Pending', 'Approved', 'Ordered', 'Received'],
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
                    'display' => false,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'y' => [
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
