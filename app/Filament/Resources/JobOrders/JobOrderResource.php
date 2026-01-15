<?php

namespace App\Filament\Resources\JobOrders;

use App\Filament\Resources\JobOrders\Pages\CreateJobOrder;
use App\Filament\Resources\JobOrders\Pages\EditJobOrder;
use App\Filament\Resources\JobOrders\Pages\ListJobOrders;
use App\Filament\Resources\JobOrders\RelationManagers\DeliveriesRelationManager;
use App\Filament\Resources\JobOrders\RelationManagers\ExpensesRelationManager;
use App\Filament\Resources\JobOrders\RelationManagers\InvoicesRelationManager;
use App\Filament\Resources\JobOrders\RelationManagers\ManPowersRelationManager;
use App\Filament\Resources\JobOrders\RelationManagers\OtherCostsRelationManager;
use App\Filament\Resources\JobOrders\RelationManagers\ProductionProgressRelationManager;
use App\Filament\Resources\JobOrders\RelationManagers\PurchaseOrdersRelationManager;
use App\Filament\Resources\JobOrders\RelationManagers\SalariesRelationManager;
use App\Filament\Resources\JobOrders\Schemas\JobOrderForm;
use App\Filament\Resources\JobOrders\Tables\JobOrdersTable;
use App\Models\JobOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class JobOrderResource extends Resource
{
    protected static ?string $model = JobOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Job Order';

    protected static ?string $pluralModelLabel = 'Job Orders';

    protected static ?string $navigationLabel = 'Job Order';

    public static function form(Schema $schema): Schema
    {
        return JobOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PurchaseOrdersRelationManager::class,
            ExpensesRelationManager::class,
            SalariesRelationManager::class,
            InvoicesRelationManager::class,
            DeliveriesRelationManager::class,
            ManPowersRelationManager::class,
            ProductionProgressRelationManager::class,
            OtherCostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobOrders::route('/'),
            'create' => CreateJobOrder::route('/create'),
            'edit' => EditJobOrder::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
