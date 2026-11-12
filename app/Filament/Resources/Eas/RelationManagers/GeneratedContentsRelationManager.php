<?php

namespace App\Filament\Resources\Eas\RelationManagers;

use App\Filament\Resources\GeneratedContents\GeneratedContentResource;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GeneratedContentsRelationManager extends RelationManager
{
    protected static string $relationship = 'generatedContents';

    protected static ?string $title = 'Generated Contents';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('updated_at')->label('Last generated')->dateTime()->sortable(),
            ])
            ->recordUrl(fn ($record) => GeneratedContentResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make()->url(fn ($record) => GeneratedContentResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
