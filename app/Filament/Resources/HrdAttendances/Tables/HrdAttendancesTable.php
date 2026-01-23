<?php

namespace App\Filament\Resources\HrdAttendances\Tables;

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

class HrdAttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'staff' => 'success',
                        'daily' => 'warning',
                        'borongan' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'staff' => 'Staf',
                        'daily' => 'Daily',
                        'borongan' => 'Borongan',
                        default => $state,
                    }),
                TextColumn::make('present_count')
                    ->label('Hadir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('absent_count')
                    ->label('Absen')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('deduction_count')
                    ->label('Pengurangan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('new_hires_count')
                    ->label('Karyawan Baru')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'staff' => 'Staf',
                        'daily' => 'Daily',
                        'borongan' => 'Borongan',
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
            ->defaultSort('date', 'desc');
    }
}
