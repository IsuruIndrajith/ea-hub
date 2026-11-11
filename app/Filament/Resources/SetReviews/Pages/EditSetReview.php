<?php

namespace App\Filament\Resources\SetReviews\Pages;

use App\Filament\Resources\SetReviews\SetReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSetReview extends EditRecord
{
    protected static string $resource = SetReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
