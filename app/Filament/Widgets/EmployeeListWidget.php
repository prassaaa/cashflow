<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class EmployeeListWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Daftar Karyawan Aktif';

    public static function canView(): bool
    {
        // HRD and super_admin can see employee list
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'hrd']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Employee::query()
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'staff' => 'success',
                        'daily' => 'warning',
                        'contract' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('daily_rate')
                    ->label('Gaji Harian')
                    ->money('IDR')
                    ->alignEnd(),

                TextColumn::make('join_date')
                    ->label('Tanggal Masuk')
                    ->date('d M Y'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}
