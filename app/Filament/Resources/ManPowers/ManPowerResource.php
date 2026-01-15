<?php

namespace App\Filament\Resources\ManPowers;

use App\Filament\Resources\ManPowers\Pages\CreateManPower;
use App\Filament\Resources\ManPowers\Pages\EditManPower;
use App\Filament\Resources\ManPowers\Pages\ListManPowers;
use App\Filament\Resources\ManPowers\Schemas\ManPowerForm;
use App\Filament\Resources\ManPowers\Tables\ManPowersTable;
use App\Models\ManPower;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManPowerResource extends Resource
{
    protected static ?string $model = ManPower::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ManPowerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManPowersTable::configure($table);
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
            'index' => ListManPowers::route('/'),
            'create' => CreateManPower::route('/create'),
            'edit' => EditManPower::route('/{record}/edit'),
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
