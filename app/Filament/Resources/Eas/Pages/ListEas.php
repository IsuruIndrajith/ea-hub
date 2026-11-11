<?php

namespace App\Filament\Resources\Eas\Pages;

use App\Filament\Resources\Eas\EaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEas extends ListRecords
{
    protected static string $resource = EaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
