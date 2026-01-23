<?php

namespace App\Filament\Resources\Deliveries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeliveryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengiriman')
                    ->columns(2)
                    ->schema([
                        TextInput::make('delivery_number')
                            ->label('No. Pengiriman')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('job_order_id')
                            ->label('Job Order')
                            ->relationship('jobOrder', 'jo_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('shipment_method')
                            ->label('Metode Pengiriman')
                            ->required()
                            ->options([
                                'courier' => 'Kurir',
                                'self_pickup' => 'Ambil Sendiri',
                                'cargo' => 'Kargo',
                                'expedition' => 'Ekspedisi',
                            ])
                            ->native(false),
                        TextInput::make('tracking_number')
                            ->label('No. Resi')
                            ->maxLength(255)
                            ->helperText('Opsional untuk self pickup'),
                    ]),
                Section::make('Status & Tanggal')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('delivery_date')
                            ->label('Tanggal Kirim')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('received_date')
                            ->label('Tanggal Diterima')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'preparing' => 'Disiapkan',
                                'shipped' => 'Dikirim',
                                'in_transit' => 'Dalam Perjalanan',
                                'delivered' => 'Diterima',
                                'returned' => 'Dikembalikan',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('preparing')
                            ->native(false),
                    ]),
                Section::make('Catatan')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3),
                    ]),
            ]);
    }
}
