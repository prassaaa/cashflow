<?php

namespace App\Filament\Resources\ManPowers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ManPowersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jobOrder.jo_number')
                    ->label('No. JO')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('employee.name')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('work_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('hours_worked')
                    ->label('Jam')
                    ->numeric(decimalPlaces: 1)
                    ->suffix(' jam')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->suffix(' jam')),
                TextColumn::make('rate_per_hour')
                    ->label('Tarif/Jam')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_cost')
                    ->label('Total Biaya')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('IDR')),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('job_order_id')
                    ->label('Job Order')
                    ->relationship('jobOrder', 'jo_number')
                    ->searchable()
                    ->preload(),
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
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('work_date', 'desc');
    }
}
