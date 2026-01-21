<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use App\Filament\Resources\OtherCosts\OtherCostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class OtherCostsRelationManager extends RelationManager
{
    protected static string $relationship = 'otherCosts';

    protected static ?string $relatedResource = OtherCostResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
