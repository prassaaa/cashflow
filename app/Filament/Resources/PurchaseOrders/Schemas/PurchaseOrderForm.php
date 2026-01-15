<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Models\JobOrder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Purchase Order')
                    ->schema([
                        TextInput::make('po_number')
                            ->label('No. PO')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('PO-001'),
                        Select::make('job_order_id')
                            ->label('Job Order')
                            ->relationship('jobOrder', 'jo_number')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih JO (kosongkan jika non-JO)')
                            ->helperText('Kosongkan untuk pembelian non-JO (aset/mesin/fasilitas)'),
                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'jo_related' => 'Terkait JO',
                                'asset' => 'Aset',
                                'machine' => 'Mesin',
                                'facility' => 'Fasilitas',
                                'other' => 'Lainnya',
                            ])
                            ->default('jo_related')
                            ->required(),
                        TextInput::make('supplier_name')
                            ->label('Nama Supplier')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                Section::make('Nilai & Tanggal')
                    ->schema([
                        TextInput::make('value')
                            ->label('Nilai PO')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(0),
                        DatePicker::make('po_date')
                            ->label('Tanggal PO')
                            ->required()
                            ->default(now()),
                        DatePicker::make('expected_delivery_date')
                            ->label('Estimasi Pengiriman'),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Disetujui',
                                'received' => 'Diterima',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),
                Section::make('Keterangan')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
