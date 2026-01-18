<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Salary;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SalaryStatsWidget extends ChartWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Statistik Gaji (6 Bulan Terakhir)';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '350px';

    public static function canView(): bool
    {
        // HRD and super_admin can see salary stats
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'hrd']);
    }

    protected function getData(): array
    {
        $months = collect();
        $totalSalaryData = collect();
        $pendingData = collect();
        $approvedData = collect();
        $paidData = collect();

        // Get last 6 months data
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));
            $period = $date->format('Y-m');

            // Total salary per month
            $totalSalary = Salary::where('period', $period)->sum('total');
            $totalSalaryData->push($totalSalary);

            // Salary by status
            $pending = Salary::where('period', $period)
                ->where('status', 'pending')
                ->sum('total');
            $pendingData->push($pending);

            $approved = Salary::where('period', $period)
                ->where('status', 'approved')
                ->sum('total');
            $approvedData->push($approved);

            $paid = Salary::where('period', $period)
                ->where('status', 'paid')
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
