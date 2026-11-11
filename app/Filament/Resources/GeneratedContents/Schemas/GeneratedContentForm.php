<?php

namespace App\Filament\Resources\GeneratedContents\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class GeneratedContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ea_id')->relationship('ea', 'name')->searchable()->preload()->required()->live(),
                Select::make('type')->options(['product_page' => 'Product page', 'set_review' => 'Set review'])->required()
                    ->rules(function (Get $get, ?Model $record): array {
                        $rule = Rule::unique('generated_contents', 'type')->where('ea_id', $get('ea_id'));

                        return [$record ? $rule->ignore($record->getKey()) : $rule];
                    }),
                Textarea::make('content')->required()->rows(30)->columnSpanFull()
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)
                    ->dehydrateStateUsing(fn ($state) => json_decode($state, true, 512, JSON_THROW_ON_ERROR))
                    ->rules(['required', 'json']),
            ]);
    }
}
