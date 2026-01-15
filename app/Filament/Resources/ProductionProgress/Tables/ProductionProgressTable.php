<?php

namespace App\Filament\Resources\ProductionProgress\Tables;

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

class ProductionProgressTable
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
                TextColumn::make('jobOrder.project_name')
                    ->label('Project')
                    ->searchable()
                    ->limit(25),
                TextColumn::make('report_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->suffix('%')
                    ->sortable()
                    ->color(fn ($state): string => match (true) {
                        $state >= 100 => 'success',
                        $state >= 75 => 'info',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->badge(),
                TextColumn::make('stage')
                    ->label('Tahap')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'planning' => 'gray',
                        'material_prep' => 'warning',
                        'production' => 'info',
                        'quality_check' => 'primary',
                        'finishing' => 'success',
                        'packing' => 'success',
                        'completed' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'planning' => 'Perencanaan',
                        'material_prep' => 'Persiapan Material',
                        'production' => 'Produksi',
                        'quality_check' => 'Quality Check',
                        'finishing' => 'Finishing',
                        'packing' => 'Packing',
                        'completed' => 'Selesai',
                    }),
                TextColumn::make('issues')
                    ->label('Kendala')
                    ->limit(30)
                    ->placeholder('Tidak ada')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('job_order_id')
                    ->label('Job Order')
                    ->relationship('jobOrder', 'jo_number')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('stage')
                    ->label('Tahap')
                    ->options([
                        'planning' => 'Perencanaan',
                        'material_prep' => 'Persiapan Material',
                        'production' => 'Produksi',
                        'quality_check' => 'Quality Check',
                        'finishing' => 'Finishing',
                        'packing' => 'Packing',
                        'completed' => 'Selesai',
                    ]),
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
            ->defaultSort('report_date', 'desc');
    }
}
