<?php

namespace App\Filament\Resources\Eas\Pages;

use App\Filament\Resources\Eas\EaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEa extends ViewRecord
{
    protected static string $resource = EaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
