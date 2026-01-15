<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use App\Filament\Resources\ManPowers\ManPowerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProductionProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'productionProgress';

    protected static ?string $relatedResource = ManPowerResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
