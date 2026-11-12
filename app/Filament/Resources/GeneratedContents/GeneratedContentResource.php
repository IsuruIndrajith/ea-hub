<?php

namespace App\Filament\Resources\GeneratedContents;

use App\Filament\Resources\GeneratedContents\Pages\CreateGeneratedContent;
use App\Filament\Resources\GeneratedContents\Pages\EditGeneratedContent;
use App\Filament\Resources\GeneratedContents\Pages\ListGeneratedContents;
use App\Filament\Resources\GeneratedContents\Pages\ViewGeneratedContent;
use App\Filament\Resources\GeneratedContents\Schemas\GeneratedContentForm;
use App\Filament\Resources\GeneratedContents\Tables\GeneratedContentsTable;
use App\Models\GeneratedContent;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GeneratedContentResource extends Resource
{
    protected static ?string $model = GeneratedContent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static \UnitEnum|string|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Generated Content';

    protected static ?string $modelLabel = 'Generated Content';

    protected static ?string $pluralModelLabel = 'Generated Content';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'ea_id';

    public static function canCreate(): bool
    {
        return false;
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
        return GeneratedContentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GeneratedContentsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('ea.name')->label('EA'),
            TextEntry::make('type')->badge(),
            TextEntry::make('content')->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))->columnSpanFull(),
            TextEntry::make('updated_at')->dateTime(),
        ]);
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
            'index' => ListGeneratedContents::route('/'),
            'create' => CreateGeneratedContent::route('/create'),
            'view' => ViewGeneratedContent::route('/{record}'),
            'edit' => EditGeneratedContent::route('/{record}/edit'),
        ];
    }
}
