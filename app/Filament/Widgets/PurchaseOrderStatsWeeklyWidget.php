<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderStatsWeeklyWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Status Purchase Order Bulan Ini (Per Minggu)';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '350px';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'purchasing']);
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
        $pendingData = collect();
        $approvedData = collect();
        $orderedData = collect();
        $receivedData = collect();

        foreach ($weeks as $week) {
            $labels->push($week['label']);
            $start = $week['start'];
            $end = $week['end'];

            $pending = PurchaseOrder::where('status', 'pending')
                ->whereBetween('po_date', [$start, $end])
                ->sum('value');
            $pendingData->push($pending);

            $approved = PurchaseOrder::where('status', 'approved')
                ->whereBetween('po_date', [$start, $end])
                ->sum('value');
            $approvedData->push($approved);

            $ordered = PurchaseOrder::where('status', 'ordered')
                ->whereBetween('po_date', [$start, $end])
                ->sum('value');
            $orderedData->push($ordered);

            $received = PurchaseOrder::where('status', 'received')
                ->whereBetween('po_date', [$start, $end])
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
            'labels' => $labels->toArray(),
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
