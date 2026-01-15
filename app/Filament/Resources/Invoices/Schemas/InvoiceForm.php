<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Invoice')
                    ->columns(2)
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label('No. Invoice')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('job_order_id')
                            ->label('Job Order')
                            ->relationship('jobOrder', 'jo_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Terkirim',
                                'partial' => 'Sebagian',
                                'paid' => 'Lunas',
                                'overdue' => 'Jatuh Tempo',
                            ])
                            ->default('draft')
                            ->native(false),
                    ]),
                Section::make('Tanggal')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('invoice_date')
                            ->label('Tanggal Invoice')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('due_date')
                            ->label('Jatuh Tempo')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('paid_date')
                            ->label('Tanggal Bayar')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
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
