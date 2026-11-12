<?php

namespace App\Filament\Resources\ProductPages;

use App\Filament\Resources\ProductPages\Pages\CreateProductPage;
use App\Filament\Resources\ProductPages\Pages\EditProductPage;
use App\Filament\Resources\ProductPages\Pages\ListProductPages;
use App\Filament\Resources\ProductPages\Pages\ViewProductPage;
use App\Filament\Resources\ProductPages\RelationManagers\FaqsRelationManager;
use App\Filament\Resources\ProductPages\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\ProductPages\RelationManagers\InternalLinksRelationManager;
use App\Filament\Resources\ProductPages\Schemas\ProductPageForm;
use App\Filament\Resources\ProductPages\Schemas\ProductPageInfolist;
use App\Filament\Resources\ProductPages\Tables\ProductPagesTable;
use App\Models\ProductPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductPageResource extends Resource
{
    protected static ?string $model = ProductPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ea_id';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return ProductPageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductPageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            FaqsRelationManager::class,
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
            'index' => ListProductPages::route('/'),
            'create' => CreateProductPage::route('/create'),
            'view' => ViewProductPage::route('/{record}'),
            'edit' => EditProductPage::route('/{record}/edit'),
        ];
    }
}
