<?php

namespace App\Filament\Resources\ProductionProgress\Pages;

use App\Filament\Resources\ProductionProgress\ProductionProgressResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProductionProgress extends EditRecord
{
    protected static string $resource = ProductionProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
