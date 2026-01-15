<?php

namespace App\Filament\Resources\Salaries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class SalariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('period')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jobOrder.jo_number')
                    ->label('No. JO')
                    ->placeholder('Non-JO')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('allowance')
                    ->label('Tunjangan')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('overtime')
                    ->label('Lembur')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deduction')
                    ->label('Potongan')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('IDR')),
                TextColumn::make('payment_date')
                    ->label('Tgl Bayar')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'paid' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'paid' => 'Dibayar',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'paid' => 'Dibayar',
                    ]),
                SelectFilter::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()->label('Export Excel'),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('period', 'desc');
    }
}
