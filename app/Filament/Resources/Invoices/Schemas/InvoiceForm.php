<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
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
                            ->maxLength(255)
                            ->placeholder('RH-03-22-25(77)4877805'),
                        Select::make('job_order_id')
                            ->label('Job Order')
                            ->relationship('jobOrder', 'jo_number')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $jobOrder = \App\Models\JobOrder::find($state);
                                    if ($jobOrder) {
                                        $set('buyer', $jobOrder->customer_name);
                                        $set('container', $jobOrder->container_name);
                                    }
                                }
                            }),
                        Select::make('shipper')
                            ->label('Shipper')
                            ->searchable()
                            ->options(fn () =>
                                \App\Models\Invoice::whereNotNull('shipper')
                                    ->distinct()
                                    ->pluck('shipper', 'shipper')
                                    ->toArray()
                            )
                            ->getSearchResultsUsing(fn (string $search): array =>
                                \App\Models\Invoice::where('shipper', 'like', "%{$search}%")
                                    ->whereNotNull('shipper')
                                    ->distinct()
                                    ->pluck('shipper', 'shipper')
                                    ->toArray()
                            )
                            ->getOptionLabelUsing(fn ($value): ?string => $value)
                            ->createOptionUsing(fn (string $value): string => $value)
                            ->allowHtml(false)
                            ->native(false)
                            ->placeholder('PT GLOBALINDO, CV ABHINAYA'),
                        TextInput::make('buyer')
                            ->label('Buyer')
                            ->maxLength(255)
                            ->placeholder('Auto-fill dari Job Order'),
                        TextInput::make('po_number')
                            ->label('PO Number')
                            ->maxLength(255)
                            ->placeholder('4877805, 34966;34967'),
                        TextInput::make('container')
                            ->label('Container')
                            ->maxLength(255)
                            ->placeholder('Auto-fill dari Job Order'),
                    ]),
                Section::make('Nilai Invoice')
                    ->columns(4)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Total Invoice')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->live(onBlur: true),
                        TextInput::make('deposit_discount')
                            ->label('Deposit/Discount')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->minValue(0)
                            ->live(onBlur: true),
                        TextInput::make('paid_amount')
                            ->label('Paid')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->minValue(0),
                        TextInput::make('balance')
                            ->label('Balance')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn ($state, $record) =>
                                $record ? number_format($record->balance, 2) : '0.00'
                            ),
                    ]),
                Section::make('Tanggal')
                    ->columns(4)
                    ->schema([
                        DatePicker::make('shipped_date')
                            ->label('Shipped Date')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
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
                Section::make('Status & Catatan')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Terkirim',
                                'paid' => 'Lunas',
                                'overdue' => 'Jatuh Tempo',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('draft')
                            ->native(false),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3),
                    ]),
            ]);
    }
}
