<?php

namespace App\Filament\Resources\HrdAttendances;

use App\Filament\Resources\HrdAttendances\Pages\CreateHrdAttendance;
use App\Filament\Resources\HrdAttendances\Pages\EditHrdAttendance;
use App\Filament\Resources\HrdAttendances\Pages\ListHrdAttendances;
use App\Filament\Resources\HrdAttendances\Schemas\HrdAttendanceForm;
use App\Filament\Resources\HrdAttendances\Tables\HrdAttendancesTable;
use App\Models\HrdAttendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class HrdAttendanceResource extends Resource
{
    protected static ?string $model = HrdAttendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'HRD';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Rekap HRD';

    protected static ?string $pluralModelLabel = 'Rekap HRD';

    protected static ?string $navigationLabel = 'Rekap HRD';

    public static function form(Schema $schema): Schema
    {
        return HrdAttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HrdAttendancesTable::configure($table);
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
            'index' => ListHrdAttendances::route('/'),
            'create' => CreateHrdAttendance::route('/create'),
            'edit' => EditHrdAttendance::route('/{record}/edit'),
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
