<?php

namespace App\Filament\Resources\Expenses\Schemas;

use App\Models\JobOrder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengeluaran')
                    ->columns(2)
                    ->schema([
                        TextInput::make('expense_number')
                            ->label('No. Pengeluaran')
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
                                'operational' => 'Operasional',
                                'material' => 'Material',
                                'transport' => 'Transportasi',
                                'utility' => 'Utilitas',
                                'maintenance' => 'Perawatan',
                                'other' => 'Lainnya',
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
                        DatePicker::make('expense_date')
                            ->label('Tanggal Pengeluaran')
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
