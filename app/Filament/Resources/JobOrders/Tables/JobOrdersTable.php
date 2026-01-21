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
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class JobOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_date')
                    ->label('Plan Date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->limit(15),
                TextColumn::make('user.name')
                    ->label('PIC')
                    ->searchable()
                    ->limit(15)
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('container_name')
                    ->label('Container')
                    ->searchable()
                    ->limit(25)
                    ->toggleable(),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('unit')
                    ->label('Unit')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('jo_number')
                    ->label('JO')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('pipa_status')
                    ->label('Pipa')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'paid' => 'LUNAS',
                        'pending' => 'Pending',
                        default => '-',
                    }),
                TextColumn::make('carton_type')
                    ->label('Carton')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'unpaid' => 'danger',
                        'partial' => 'warning',
                        'paid' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'paid' => 'LUNAS',
                        'partial' => 'Sebagian',
                        'unpaid' => 'Belum Bayar',
                        default => '-',
                    }),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('IDR')),
                TextColumn::make('status')
                    ->label('Progress')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'in_progress' => 'Proses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Batal',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('due_date')
                    ->label('Deadline')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('project_name')
                    ->label('Project')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->preload()
                    ->options(fn () => \App\Models\JobOrder::distinct()->pluck('customer_name', 'customer_name')->toArray()),
                SelectFilter::make('user_id')
                    ->label('PIC')
                    ->relationship('user', 'name', fn ($query) => $query->role('ppic'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('payment_status')
                    ->label('Payment')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'partial' => 'Sebagian',
                        'paid' => 'LUNAS',
                    ]),
                SelectFilter::make('pipa_status')
                    ->label('Pipa')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'LUNAS',
                    ]),
                SelectFilter::make('status')
                    ->label('Progress')
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
                ExportAction::make()->label('Export Excel'),
                BulkActionGroup::make([
                    ExportBulkAction::make()->label('Export Selected'),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('order_date', 'desc');
    }
}
