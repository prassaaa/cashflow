<?php

namespace App\Filament\Resources\OtherCosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OtherCostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cost_number')
                    ->label('No. Biaya')
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
                        'shipping' => 'Pengiriman',
                        'insurance' => 'Asuransi',
                        'tax' => 'Pajak',
                        'permit' => 'Perizinan',
                        'consultant' => 'Konsultan',
                        'misc' => 'Lain-lain',
                    }),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('IDR')),
                TextColumn::make('cost_date')
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
                TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'shipping' => 'Pengiriman',
                        'insurance' => 'Asuransi',
                        'tax' => 'Pajak',
                        'permit' => 'Perizinan',
                        'consultant' => 'Konsultan',
                        'misc' => 'Lain-lain',
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
            ->defaultSort('cost_date', 'desc');
    }
}
