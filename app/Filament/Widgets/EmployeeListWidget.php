<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class EmployeeListWidget extends ChartWidget
{
    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 1;

    protected ?string $heading = 'Distribusi Karyawan Aktif';

    protected ?string $maxHeight = '300px';

    public static function canView(): bool
    {
        // HRD and super_admin can see employee list
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'hrd']);
    }

    protected function getData(): array
    {
        // Count employees by type
        $staff = Employee::where('status', 'active')
            ->where('type', 'staff')
            ->count();

        $daily = Employee::where('status', 'active')
            ->where('type', 'daily')
            ->count();

        $contract = Employee::where('status', 'active')
            ->where('type', 'contract')
            ->count();

        return [
            'datasets' => [
                [
                    'data' => [$staff, $daily, $contract],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',    // green - Staff
                        'rgb(251, 146, 60)',   // orange - Daily
                        'rgb(59, 130, 246)',   // blue - Contract
                    ],
                    'borderColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(251, 146, 60)',
                        'rgb(59, 130, 246)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Staff (' . $staff . ')', 'Daily (' . $daily . ')', 'Contract (' . $contract . ')'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
                ],
            ],
        ];
    }
}
