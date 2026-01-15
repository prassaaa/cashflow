<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expense_number')
                    ->label('No. Pengeluaran')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('jobOrder.jo_number')
                    ->label('No. JO')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Non-JO')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'operational' => 'Operasional',
                        'material' => 'Material',
                        'transport' => 'Transportasi',
                        'utility' => 'Utilitas',
                        'maintenance' => 'Perawatan',
                        'other' => 'Lainnya',
                    }),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('expense_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'rejected' => 'danger',
                        'paid' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'paid' => 'Dibayar',
                    }),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'operational' => 'Operasional',
                        'material' => 'Material',
                        'transport' => 'Transportasi',
                        'utility' => 'Utilitas',
                        'maintenance' => 'Perawatan',
                        'other' => 'Lainnya',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'paid' => 'Dibayar',
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
            ->defaultSort('expense_date', 'desc');
    }
}
