<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                            ->placeholder('JO-001'),
                        TextInput::make('customer_name')
                            ->label('Nama Customer')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('project_name')
                            ->label('Nama Project')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
                Section::make('Nilai & Tanggal')
                    ->schema([
                        TextInput::make('value')
                            ->label('Nilai Order')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(0),
                        DatePicker::make('order_date')
                            ->label('Tanggal Order')
                            ->required()
                            ->default(now()),
                        DatePicker::make('due_date')
                            ->label('Tanggal Deadline'),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'Dalam Proses',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
