<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_number')
                    ->label('No. Karyawan')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'permanent' => 'success',
                        'contract' => 'info',
                        'daily' => 'warning',
                        'freelance' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'permanent' => 'Tetap',
                        'contract' => 'Kontrak',
                        'daily' => 'Harian',
                        'freelance' => 'Freelance',
                    }),
                TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable(),
                TextColumn::make('department')
                    ->label('Departemen')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'production' => 'Produksi',
                        'sales' => 'Sales',
                        'purchasing' => 'Purchasing',
                        'finance' => 'Keuangan',
                        'hrd' => 'HRD',
                        'management' => 'Management',
                    }),
                TextColumn::make('base_salary')
                    ->label('Gaji Pokok')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('join_date')
                    ->label('Tgl Masuk')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'resigned' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Non-Aktif',
                        'resigned' => 'Resign',
                    }),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'permanent' => 'Tetap',
                        'contract' => 'Kontrak',
                        'daily' => 'Harian',
                        'freelance' => 'Freelance',
                    ]),
                SelectFilter::make('department')
                    ->label('Departemen')
                    ->options([
                        'production' => 'Produksi',
                        'sales' => 'Sales',
                        'purchasing' => 'Purchasing',
                        'finance' => 'Keuangan',
                        'hrd' => 'HRD',
                        'management' => 'Management',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Non-Aktif',
                        'resigned' => 'Resign',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
