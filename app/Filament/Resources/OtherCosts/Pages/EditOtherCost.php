<?php

namespace App\Filament\Resources\OtherCosts\Pages;

use App\Filament\Resources\OtherCosts\OtherCostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditOtherCost extends EditRecord
{
    protected static string $resource = OtherCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
