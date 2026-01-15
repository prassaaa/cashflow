<?php

namespace App\Filament\Resources\ProductionProgress\Pages;

use App\Filament\Resources\ProductionProgress\ProductionProgressResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductionProgress extends ListRecords
{
    protected static string $resource = ProductionProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
