<?php

namespace App\Filament\Resources\HrdAttendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HrdAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rekap HRD')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now()),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'staff' => 'Staf',
                                'daily' => 'Daily',
                                'borongan' => 'Borongan',
                            ])
                            ->default('staff')
                            ->native(false),
                        TextInput::make('present_count')
                            ->label('Jumlah Hadir')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('absent_count')
                            ->label('Jumlah Absen')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('deduction_count')
                            ->label('Jumlah Pengurangan')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('new_hires_count')
                            ->label('Jumlah Karyawan Baru')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ]),
            ]);
    }
}
