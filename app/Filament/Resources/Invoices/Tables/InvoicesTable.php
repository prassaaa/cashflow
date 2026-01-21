<?php

namespace App\Filament\Resources\Invoices\Tables;

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

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shipped_date')
                    ->label('Shipped Date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('shipper')
                    ->label('Shipper')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('buyer')
                    ->label('Buyer')
                    ->searchable()
                    ->limit(20),
                TextColumn::make('invoice_number')
                    ->label('No Invoice')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('po_number')
                    ->label('PO Number')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('container')
                    ->label('Container')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('amount')
                    ->label('Total Invoice')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('IDR')),
                TextColumn::make('deposit_discount')
                    ->label('Deposit/Discount')
                    ->money('IDR')
                    ->toggleable(),
                TextColumn::make('balance')
                    ->label('Balance')
                    ->money('IDR')
                    ->getStateUsing(fn ($record) => $record->balance)
                    ->sortable(),
                TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->money('IDR')
                    ->toggleable(),
                TextColumn::make('paid_date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('outstanding')
                    ->label('Outstanding')
                    ->money('IDR')
                    ->getStateUsing(fn ($record) => $record->outstanding)
                    ->badge()
                    ->color(fn ($record) => $record->outstanding > 0 ? 'danger' : 'success'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'sent' => 'Terkirim',
                        'paid' => 'Lunas',
                        'overdue' => 'Jatuh Tempo',
                        'cancelled' => 'Dibatalkan',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jobOrder.jo_number')
                    ->label('No. JO')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('shipper')
                    ->label('Shipper')
                    ->searchable()
                    ->preload()
                    ->options(fn () => \App\Models\Invoice::whereNotNull('shipper')->distinct()->pluck('shipper', 'shipper')->toArray()),
                SelectFilter::make('buyer')
                    ->label('Buyer')
                    ->searchable()
                    ->preload()
                    ->options(fn () => \App\Models\Invoice::whereNotNull('buyer')->distinct()->pluck('buyer', 'buyer')->toArray()),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Terkirim',
                        'paid' => 'Lunas',
                        'overdue' => 'Jatuh Tempo',
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
            ->defaultSort('shipped_date', 'desc');
    }
}
