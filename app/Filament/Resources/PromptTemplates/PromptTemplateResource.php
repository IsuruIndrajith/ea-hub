<?php

namespace App\Filament\Resources\PromptTemplates;

use App\Filament\Resources\PromptTemplates\Pages\CreatePromptTemplate;
use App\Filament\Resources\PromptTemplates\Pages\EditPromptTemplate;
use App\Filament\Resources\PromptTemplates\Pages\ListPromptTemplates;
use App\Filament\Resources\PromptTemplates\Schemas\PromptTemplateForm;
use App\Filament\Resources\PromptTemplates\Tables\PromptTemplatesTable;
use App\Models\PromptTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromptTemplateResource extends Resource
{
    protected static ?string $model = PromptTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return PromptTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromptTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPromptTemplates::route('/'),
            'create' => CreatePromptTemplate::route('/create'),
            'edit' => EditPromptTemplate::route('/{record}/edit'),
        ];
    }
}
