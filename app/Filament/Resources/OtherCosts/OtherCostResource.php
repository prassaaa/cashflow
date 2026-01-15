<?php

namespace App\Filament\Resources\OtherCosts;

use App\Filament\Resources\OtherCosts\Pages\CreateOtherCost;
use App\Filament\Resources\OtherCosts\Pages\EditOtherCost;
use App\Filament\Resources\OtherCosts\Pages\ListOtherCosts;
use App\Filament\Resources\OtherCosts\Schemas\OtherCostForm;
use App\Filament\Resources\OtherCosts\Tables\OtherCostsTable;
use App\Models\OtherCost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OtherCostResource extends Resource
{
    protected static ?string $model = OtherCost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OtherCostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OtherCostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOtherCosts::route('/'),
            'create' => CreateOtherCost::route('/create'),
            'edit' => EditOtherCost::route('/{record}/edit'),
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
