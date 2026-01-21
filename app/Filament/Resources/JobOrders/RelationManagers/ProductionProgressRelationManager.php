<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use App\Filament\Resources\ProductionProgress\ProductionProgressResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProductionProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'productionProgress';

    protected static ?string $relatedResource = ProductionProgressResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
