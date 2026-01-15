<?php

namespace App\Filament\Resources\JobOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class JobOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jo_number')
                    ->label('No. JO')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('project_name')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('order_date')
                    ->label('Tgl Order')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Deadline')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
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
            ->defaultSort('order_date', 'desc');
    }
}
