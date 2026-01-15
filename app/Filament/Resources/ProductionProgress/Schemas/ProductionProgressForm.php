<?php

namespace App\Filament\Resources\ProductionProgress\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductionProgressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Progress')
                    ->columns(2)
                    ->schema([
                        Select::make('job_order_id')
                            ->label('Job Order')
                            ->relationship('jobOrder', 'jo_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        DatePicker::make('report_date')
                            ->label('Tanggal Laporan')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now()),
                        TextInput::make('progress_percentage')
                            ->label('Persentase Progress')
                            ->required()
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(5),
                        Select::make('stage')
                            ->label('Tahap')
                            ->required()
                            ->options([
                                'planning' => 'Perencanaan',
                                'material_prep' => 'Persiapan Material',
                                'production' => 'Produksi',
                                'quality_check' => 'Quality Check',
                                'finishing' => 'Finishing',
                                'packing' => 'Packing',
                                'completed' => 'Selesai',
                            ])
                            ->native(false),
                    ]),
                Section::make('Catatan')
                    ->columns(1)
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi Progress')
                            ->rows(3),
                        Textarea::make('issues')
                            ->label('Kendala/Issue')
                            ->rows(3)
                            ->helperText('Kosongkan jika tidak ada kendala'),
                    ]),
            ]);
    }
}
