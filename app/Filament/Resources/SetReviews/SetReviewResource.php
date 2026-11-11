<?php

namespace App\Filament\Resources\SetReviews;

use App\Filament\Resources\SetReviews\Pages\CreateSetReview;
use App\Filament\Resources\SetReviews\Pages\EditSetReview;
use App\Filament\Resources\SetReviews\Pages\ListSetReviews;
use App\Filament\Resources\SetReviews\Pages\ViewSetReview;
use App\Filament\Resources\SetReviews\RelationManagers\FaqsRelationManager;
use App\Filament\Resources\SetReviews\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\SetReviews\RelationManagers\InternalLinksRelationManager;
use App\Filament\Resources\SetReviews\RelationManagers\SetFilesRelationManager;
use App\Filament\Resources\SetReviews\Schemas\SetReviewForm;
use App\Filament\Resources\SetReviews\Schemas\SetReviewInfolist;
use App\Filament\Resources\SetReviews\Tables\SetReviewsTable;
use App\Models\SetReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SetReviewResource extends Resource
{
    protected static ?string $model = SetReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ea_id';

    public static function form(Schema $schema): Schema
    {
        return SetReviewForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SetReviewInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SetReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            FaqsRelationManager::class,
            SetFilesRelationManager::class,
            ImagesRelationManager::class,
            InternalLinksRelationManager::class,
        ];
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSetReviews::route('/'),
            'create' => CreateSetReview::route('/create'),
            'view' => ViewSetReview::route('/{record}'),
            'edit' => EditSetReview::route('/{record}/edit'),
        ];
    }
}
