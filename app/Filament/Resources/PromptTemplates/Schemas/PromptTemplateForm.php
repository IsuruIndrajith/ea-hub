<?php

namespace App\Filament\Resources\PromptTemplates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromptTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')->options(['product_page' => 'Product page', 'set_review' => 'Set review'])->required(),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('version')->required()->default('1.0'),
                Toggle::make('is_active')->default(true),
                Textarea::make('body')->required()->rows(24)->columnSpanFull(),
            ]);
    }
}
