<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class OverdueInvoicesWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Invoice Jatuh Tempo';

    public static function canView(): bool
    {
        // super_admin, marketing, and accounting can see overdue invoices
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'marketing', 'accounting']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Invoice::query()
                    ->where('status', '!=', 'paid')
                    ->where('due_date', '<', now())
                    ->orderBy('due_date')
            )
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->badge()
                    ->color('danger'),
                TextColumn::make('jobOrder.jo_number')
                    ->label('No. JO')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('jobOrder.customer_name')
                    ->label('Customer')
                    ->limit(20),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR'),
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->color('danger'),
                TextColumn::make('days_overdue')
                    ->label('Telat')
                    ->getStateUsing(fn ($record) => now()->diffInDays($record->due_date) . ' hari')
                    ->badge()
                    ->color('danger'),
            ])
            ->paginated(false)
            ->emptyStateHeading('Tidak ada invoice jatuh tempo')
            ->emptyStateDescription('Semua invoice dalam kondisi baik')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
