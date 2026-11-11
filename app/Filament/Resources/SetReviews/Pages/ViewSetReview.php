<?php

namespace App\Filament\Resources\SetReviews\Pages;

use App\Filament\Resources\SetReviews\SetReviewResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSetReview extends ViewRecord
{
    protected static string $resource = SetReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
