<?php

namespace App\Filament\Resources\ManPowers\Pages;

use App\Filament\Resources\ManPowers\ManPowerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditManPower extends EditRecord
{
    protected static string $resource = ManPowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
