<?php

namespace App\Filament\Resources\ManPowers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class ManPowerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tenaga Kerja')
                    ->columns(2)
                    ->schema([
                        Select::make('job_order_id')
                            ->label('Job Order')
                            ->relationship('jobOrder', 'jo_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('employee_id')
                            ->label('Karyawan')
                            ->relationship('employee', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        DatePicker::make('work_date')
                            ->label('Tanggal Kerja')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        TextInput::make('hours_worked')
                            ->label('Jam Kerja')
                            ->required()
                            ->numeric()
                            ->suffix('jam')
                            ->minValue(0)
                            ->step(0.5)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                    ]),
                Section::make('Biaya')
                    ->columns(3)
                    ->schema([
                        TextInput::make('rate_per_hour')
                            ->label('Tarif per Jam')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                        TextInput::make('total_cost')
                            ->label('Total Biaya')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),
                    ]),
                Section::make('Keterangan')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi Pekerjaan')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function calculateTotal(Get $get, Set $set): void
    {
        $hours = (float) ($get('hours_worked') ?? 0);
        $rate = (float) ($get('rate_per_hour') ?? 0);

        $total = $hours * $rate;
        $set('total_cost', $total);
    }
}
