<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Job Order')
                    ->schema([
                        TextInput::make('jo_number')
                            ->label('No. JO')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('JOGF-XXX-VIII-001'),
                        TextInput::make('customer_name')
                            ->label('Customer')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('pic')
                            ->label('PIC')
                            ->maxLength(255)
                            ->placeholder('Person In Charge'),
                        TextInput::make('project_name')
                            ->label('Nama Project')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('container_name')
                            ->label('Container')
                            ->maxLength(255)
                            ->placeholder('Nama Container'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
                Section::make('Quantity & Nilai')
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Qty')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0),
                        Select::make('unit')
                            ->label('Satuan')
                            ->options([
                                'PC' => 'PC',
                                'PP' => 'PP',
                                'SET' => 'SET',
                                'PCS' => 'PCS',
                            ])
                            ->default('PC')
                            ->required()
                            ->native(false),
                        TextInput::make('value')
                            ->label('Nilai')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(0),
                        TextInput::make('carton_type')
                            ->label('Carton')
                            ->maxLength(100)
                            ->placeholder('RSA, INHOUSE, dll'),
                    ])->columns(4),
                Section::make('Tanggal')
                    ->schema([
                        DatePicker::make('order_date')
                            ->label('Plan Date')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('due_date')
                            ->label('Deadline')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])->columns(2),
                Section::make('Status')
                    ->schema([
                        Select::make('status')
                            ->label('Progress')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'Dalam Proses',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),
                        Select::make('pipa_status')
                            ->label('Pipa')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'LUNAS',
                            ])
                            ->default('pending')
                            ->native(false),
                        Select::make('payment_status')
                            ->label('Payment')
                            ->options([
                                'unpaid' => 'Belum Bayar',
                                'partial' => 'Sebagian',
                                'paid' => 'LUNAS',
                            ])
                            ->default('unpaid')
                            ->native(false),
                    ])->columns(3),
            ]);
    }
}
