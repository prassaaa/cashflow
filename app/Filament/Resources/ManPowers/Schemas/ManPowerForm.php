<?php

namespace App\Filament\Resources\ManPowers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Components\Section;
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
                        Select::make('payment_type')
                            ->label('Tipe Pembayaran')
                            ->required()
                            ->options([
                                'hourly' => 'Per Jam',
                                'borongan' => 'Borongan',
                            ])
                            ->default('hourly')
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => self::resetFields($set)),
                    ]),
                Section::make('Jam Kerja')
                    ->columns(2)
                    ->schema([
                        TextInput::make('hours_worked')
                            ->label('Jam Kerja')
                            ->required()
                            ->numeric()
                            ->suffix('jam')
                            ->minValue(0)
                            ->step(0.5)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                        TextInput::make('rate_per_hour')
                            ->label('Tarif per Jam')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                    ])
                    ->visible(fn (Get $get): bool => $get('payment_type') === 'hourly'),
                Section::make('Borongan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Jumlah Unit')
                            ->required()
                            ->numeric()
                            ->suffix('unit')
                            ->minValue(0)
                            ->step(0.5)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                        TextInput::make('rate_per_unit')
                            ->label('Tarif per Unit')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                    ])
                    ->visible(fn (Get $get): bool => $get('payment_type') === 'borongan'),
                Section::make('Total')
                    ->schema([
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

    public static function resetFields(Set $set): void
    {
        $set('hours_worked', 0);
        $set('rate_per_hour', 0);
        $set('quantity', 0);
        $set('rate_per_unit', 0);
        $set('total_cost', 0);
    }

    public static function calculateTotal(Get $get, Set $set): void
    {
        $paymentType = $get('payment_type') ?? 'hourly';

        if ($paymentType === 'hourly') {
            $hours = (float) ($get('hours_worked') ?? 0);
            $rate = (float) ($get('rate_per_hour') ?? 0);
            $total = $hours * $rate;
        } else {
            $quantity = (float) ($get('quantity') ?? 0);
            $rate = (float) ($get('rate_per_unit') ?? 0);
            $total = $quantity * $rate;
        }

        $set('total_cost', $total);
    }
}
