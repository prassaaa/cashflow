<?php

namespace App\Filament\Widgets;

use App\Models\Salary;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SalaryStatsWeeklyWidget extends ChartWidget
{
    protected static ?int $sort = 6;

    protected ?string $heading = 'Statistik Gaji Bulan Ini (Per Minggu)';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '350px';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'hrd']);
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
        $totalSalaryData = collect();
        $pendingData = collect();
        $approvedData = collect();
        $paidData = collect();

        $currentPeriod = Carbon::now()->format('Y-m');

        foreach ($weeks as $week) {
            $labels->push($week['label']);
            $start = $week['start'];
            $end = $week['end'];

            // Total salary - berdasarkan payment_date jika ada, atau created_at
            $totalSalary = Salary::where('period', $currentPeriod)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('payment_date', [$start, $end])
                        ->orWhere(function ($q) use ($start, $end) {
                            $q->whereNull('payment_date')
                                ->whereBetween('created_at', [$start, $end]);
                        });
                })
                ->sum('total');
            $totalSalaryData->push($totalSalary);

            // Salary by status
            $pending = Salary::where('period', $currentPeriod)
                ->where('status', 'pending')
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                })
                ->sum('total');
            $pendingData->push($pending);

            $approved = Salary::where('period', $currentPeriod)
                ->where('status', 'approved')
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                })
                ->sum('total');
            $approvedData->push($approved);

            $paid = Salary::where('period', $currentPeriod)
                ->where('status', 'paid')
                ->whereBetween('payment_date', [$start, $end])
                ->sum('total');
            $paidData->push($paid);
        }

        return [
            'datasets' => [
                [
                    'type' => 'line',
                    'label' => 'Total Gaji',
                    'data' => $totalSalaryData->toArray(),
                    'borderColor' => 'rgb(99, 102, 241)',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'borderWidth' => 3,
                    'tension' => 0.4,
                    'fill' => false,
                    'yAxisID' => 'y',
                    'order' => 0,
                ],
                [
                    'type' => 'bar',
                    'label' => 'Pending',
                    'data' => $pendingData->toArray(),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.7)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 1,
                    'yAxisID' => 'y1',
                    'order' => 1,
                ],
                [
                    'type' => 'bar',
                    'label' => 'Approved',
                    'data' => $approvedData->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                    'yAxisID' => 'y1',
                    'order' => 2,
                ],
                [
                    'type' => 'bar',
                    'label' => 'Paid',
                    'data' => $paidData->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.7)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                    'yAxisID' => 'y1',
                    'order' => 3,
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
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Total Gaji',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'stacked' => true,
                    'beginAtZero' => true,
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Status',
                    ],
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
