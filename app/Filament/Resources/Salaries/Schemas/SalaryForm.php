<?php

namespace App\Filament\Resources\Salaries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Gaji')
                    ->columns(2)
                    ->schema([
                        Select::make('employee_id')
                            ->label('Karyawan')
                            ->relationship('employee', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('job_order_id')
                            ->label('Job Order')
                            ->relationship('jobOrder', 'jo_number')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih JO (opsional)')
                            ->helperText('Kosongkan jika tidak terkait JO'),
                        TextInput::make('period')
                            ->label('Periode')
                            ->required()
                            ->placeholder('contoh: 2024-01')
                            ->maxLength(7),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending',
                                'paid' => 'Dibayar',
                            ])
                            ->default('draft')
                            ->native(false),
                    ]),
                Section::make('Komponen Gaji')
                    ->columns(2)
                    ->schema([
                        TextInput::make('basic_salary')
                            ->label('Gaji Pokok')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                        TextInput::make('allowance')
                            ->label('Tunjangan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                        TextInput::make('overtime')
                            ->label('Lembur')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                        TextInput::make('deduction')
                            ->label('Potongan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),
                        TextInput::make('total')
                            ->label('Total')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),
                        DatePicker::make('payment_date')
                            ->label('Tanggal Pembayaran')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ]),
            ]);
    }

    public static function calculateTotal(Get $get, Set $set): void
    {
        $basic = (float) ($get('basic_salary') ?? 0);
        $allowance = (float) ($get('allowance') ?? 0);
        $overtime = (float) ($get('overtime') ?? 0);
        $deduction = (float) ($get('deduction') ?? 0);

        $total = $basic + $allowance + $overtime - $deduction;
        $set('total', $total);
    }
}
