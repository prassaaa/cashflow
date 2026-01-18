<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class LatestPurchaseOrdersWidget extends TableWidget
{
    protected static ?int $sort = 9;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Purchase Order Terbaru';

    public static function canView(): bool
    {
        // Purchasing and super_admin can see latest POs
        $user = Auth::user();
        return $user?->hasAnyRole(['super_admin', 'purchasing']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PurchaseOrder::query()
                    ->with('jobOrder')
                    ->latest('po_date')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('po_number')
                    ->label('No. PO')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('jobOrder.jo_number')
                    ->label('No. JO')
                    ->searchable(),

                TextColumn::make('supplier')
                    ->label('Supplier')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'material' => 'primary',
                        'consumable' => 'info',
                        'tool' => 'warning',
                        'service' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('value')
                    ->label('Nilai')
                    ->money('IDR')
                    ->alignEnd(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'ordered' => 'primary',
                        'received' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('po_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
