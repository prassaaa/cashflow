<?php

namespace App\Filament\Resources\ProductionProgress;

use App\Filament\Resources\ProductionProgress\Pages\CreateProductionProgress;
use App\Filament\Resources\ProductionProgress\Pages\EditProductionProgress;
use App\Filament\Resources\ProductionProgress\Pages\ListProductionProgress;
use App\Filament\Resources\ProductionProgress\Schemas\ProductionProgressForm;
use App\Filament\Resources\ProductionProgress\Tables\ProductionProgressTable;
use App\Models\ProductionProgress;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductionProgressResource extends Resource
{
    protected static ?string $model = ProductionProgress::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductionProgressForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductionProgressTable::configure($table);
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
            'index' => ListProductionProgress::route('/'),
            'create' => CreateProductionProgress::route('/create'),
            'edit' => EditProductionProgress::route('/{record}/edit'),
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
