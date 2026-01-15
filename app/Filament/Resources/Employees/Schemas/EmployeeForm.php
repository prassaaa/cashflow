<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Karyawan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('employee_number')
                            ->label('No. Karyawan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->label('Tipe')
                            ->required()
                            ->options([
                                'staff' => 'Staff Tetap',
                                'contract' => 'Kontrak',
                                'daily' => 'Harian',
                            ])
                            ->native(false),
                        TextInput::make('position')
                            ->label('Jabatan')
                            ->required()
                            ->maxLength(255),
                        Select::make('department')
                            ->label('Departemen')
                            ->required()
                            ->options([
                                'production' => 'Produksi',
                                'marketing' => 'Marketing',
                                'purchasing' => 'Purchasing',
                                'accounting' => 'Accounting',
                                'hrd' => 'HRD',
                                'warehouse' => 'Warehouse',
                            ])
                            ->native(false),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Non-Aktif',
                                'resigned' => 'Resign',
                            ])
                            ->default('active')
                            ->native(false),
                    ]),
                Section::make('Gaji & Tanggal')
                    ->columns(3)
                    ->schema([
                        TextInput::make('base_salary')
                            ->label('Gaji Pokok')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                        DatePicker::make('join_date')
                            ->label('Tanggal Masuk')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('end_date')
                            ->label('Tanggal Keluar')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->helperText('Kosongkan jika masih aktif'),
                    ]),
            ]);
    }
}
