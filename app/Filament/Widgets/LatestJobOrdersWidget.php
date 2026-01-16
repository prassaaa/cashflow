<?php

namespace App\Filament\Widgets;

use App\Models\JobOrder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class LatestJobOrdersWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Job Order Terbaru';

    public static function canView(): bool
    {
        // super_admin, marketing, and ppic can see job orders
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'marketing', 'ppic']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JobOrder::query()
                    ->latest('order_date')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('jo_number')
                    ->label('No. JO')
                    ->searchable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->limit(20),
                TextColumn::make('project_name')
                    ->label('Project')
                    ->limit(25),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->money('IDR'),
                TextColumn::make('order_date')
                    ->label('Tgl Order')
                    ->date('d M Y'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'in_progress' => 'Proses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Batal',
                    }),
            ])
            ->paginated(false);
    }
}
