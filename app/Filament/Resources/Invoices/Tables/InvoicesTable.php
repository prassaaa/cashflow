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
                TextColumn::make('invoice_number')
                    ->label('No. Invoice')
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
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('IDR')),
                TextColumn::make('invoice_date')
                    ->label('Tgl Invoice')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date < now() && $record->status !== 'paid' ? 'danger' : null),
                TextColumn::make('paid_date')
                    ->label('Tgl Bayar')
                    ->date('d M Y')
                    ->placeholder('Belum dibayar')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    }),
            ])
            ->filters([
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
            ->defaultSort('invoice_date', 'desc');
    }
}
