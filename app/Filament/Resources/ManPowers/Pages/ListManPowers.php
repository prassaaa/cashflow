<?php

namespace App\Filament\Resources\ManPowers\Pages;

use App\Filament\Resources\ManPowers\ManPowerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManPowers extends ListRecords
{
    protected static string $resource = ManPowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
