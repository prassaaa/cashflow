<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Salary;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SalaryStatsWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        // HRD and super_admin can see salary stats
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'hrd']);
    }

    protected function getStats(): array
    {
        $currentMonth = Carbon::now()->format('Y-m');

        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();

        $salaryThisMonth = Salary::where('period', $currentMonth)->sum('total_salary');
        $pendingSalaries = Salary::where('period', $currentMonth)
            ->where('status', 'pending')
            ->count();
        $approvedSalaries = Salary::where('period', $currentMonth)
            ->where('status', 'approved')
            ->count();
        $paidSalaries = Salary::where('period', $currentMonth)
            ->where('status', 'paid')
            ->count();

        return [
            Stat::make('Total Karyawan', $totalEmployees)
                ->description('Semua karyawan')
                ->color('primary')
                ->icon('heroicon-o-users'),

            Stat::make('Karyawan Aktif', $activeEmployees)
                ->description('Status aktif')
                ->color('success')
                ->icon('heroicon-o-user-circle'),

            Stat::make('Total Gaji Bulan Ini', 'Rp ' . number_format($salaryThisMonth, 0, ',', '.'))
                ->description($currentMonth)
                ->color('warning')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Gaji Pending', $pendingSalaries)
                ->description('Menunggu approval')
                ->color('danger')
                ->icon('heroicon-o-clock'),

            Stat::make('Gaji Approved', $approvedSalaries)
                ->description('Siap dibayar')
                ->color('info')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Gaji Dibayar', $paidSalaries)
                ->description('Sudah dibayar')
                ->color('success')
                ->icon('heroicon-o-check-badge'),
        ];
    }
}
