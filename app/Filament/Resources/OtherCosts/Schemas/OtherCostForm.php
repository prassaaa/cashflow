<?php

namespace App\Filament\Resources\OtherCosts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OtherCostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Biaya Lainnya')
                    ->columns(2)
                    ->schema([
                        TextInput::make('cost_number')
                            ->label('No. Biaya')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('job_order_id')
                            ->label('Job Order')
                            ->relationship('jobOrder', 'jo_number')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih JO (opsional)')
                            ->helperText('Kosongkan jika tidak terkait JO'),
                        Select::make('category')
                            ->label('Kategori')
                            ->required()
                            ->options([
                                'shipping' => 'Pengiriman',
                                'insurance' => 'Asuransi',
                                'tax' => 'Pajak',
                                'permit' => 'Perizinan',
                                'consultant' => 'Konsultan',
                                'misc' => 'Lain-lain',
                            ])
                            ->native(false),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                    ]),
                Section::make('Detail')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('cost_date')
                            ->label('Tanggal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'paid' => 'Dibayar',
                            ])
                            ->default('pending')
                            ->native(false),
                        Textarea::make('description')
                            ->label('Keterangan')
                            ->columnSpanFull()
                            ->rows(3),
                    ]),
            ]);
    }
}
