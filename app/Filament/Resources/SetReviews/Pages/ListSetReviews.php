<?php

namespace App\Filament\Resources\SetReviews\Pages;

use App\Filament\Resources\SetReviews\SetReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSetReviews extends ListRecords
{
    protected static string $resource = SetReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
