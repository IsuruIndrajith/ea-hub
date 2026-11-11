<?php

namespace App\Filament\Resources\ProductPages\Pages;

use App\Filament\Resources\ProductPages\ProductPageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProductPage extends ViewRecord
{
    protected static string $resource = ProductPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
