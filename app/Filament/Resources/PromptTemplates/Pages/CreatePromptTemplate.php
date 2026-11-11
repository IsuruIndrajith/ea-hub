<?php

namespace App\Filament\Resources\PromptTemplates\Pages;

use App\Filament\Resources\PromptTemplates\PromptTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePromptTemplate extends CreateRecord
{
    protected static string $resource = PromptTemplateResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->is_active) {
            $this->record->activate();
        }
    }
}
