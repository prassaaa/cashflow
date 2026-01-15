<?php

namespace App\Filament\Resources\Deliveries\Tables;

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

class DeliveriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('delivery_number')
                    ->label('No. Pengiriman')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('jobOrder.jo_number')
                    ->label('No. JO')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('jobOrder.customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->limit(20),
                TextColumn::make('shipment_method')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'courier' => 'Kurir',
                        'self_pickup' => 'Ambil Sendiri',
                        'cargo' => 'Kargo',
                        'expedition' => 'Ekspedisi',
                    }),
                TextColumn::make('tracking_number')
                    ->label('No. Resi')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),
                TextColumn::make('delivery_date')
                    ->label('Tgl Kirim')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('received_date')
                    ->label('Tgl Diterima')
                    ->date('d M Y')
                    ->placeholder('Belum diterima')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'preparing' => 'gray',
                        'shipped' => 'info',
                        'in_transit' => 'warning',
                        'delivered' => 'success',
                        'returned' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'preparing' => 'Disiapkan',
                        'shipped' => 'Dikirim',
                        'in_transit' => 'Dalam Perjalanan',
                        'delivered' => 'Diterima',
                        'returned' => 'Dikembalikan',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'preparing' => 'Disiapkan',
                        'shipped' => 'Dikirim',
                        'in_transit' => 'Dalam Perjalanan',
                        'delivered' => 'Diterima',
                        'returned' => 'Dikembalikan',
                    ]),
                SelectFilter::make('shipment_method')
                    ->label('Metode')
                    ->options([
                        'courier' => 'Kurir',
                        'self_pickup' => 'Ambil Sendiri',
                        'cargo' => 'Kargo',
                        'expedition' => 'Ekspedisi',
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
            ->defaultSort('delivery_date', 'desc');
    }
}
