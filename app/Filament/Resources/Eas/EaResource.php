<?php

namespace App\Filament\Resources\Eas;

use App\Filament\Resources\Eas\Pages\CreateEa;
use App\Filament\Resources\Eas\Pages\EditEa;
use App\Filament\Resources\Eas\Pages\ListEas;
use App\Filament\Resources\Eas\Pages\ViewEa;
use App\Filament\Resources\Eas\RelationManagers\GeneratedContentsRelationManager;
use App\Filament\Resources\Eas\RelationManagers\SignalsRelationManager;
use App\Filament\Resources\Eas\Schemas\EaForm;
use App\Filament\Resources\Eas\Schemas\EaInfolist;
use App\Filament\Resources\Eas\Tables\EasTable;
use App\Models\Ea;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EaResource extends Resource
{
    protected static ?string $model = Ea::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return EaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SignalsRelationManager::class,
            GeneratedContentsRelationManager::class,
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

    public static function canForceDelete($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canForceDeleteAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canRestore($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canRestoreAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEas::route('/'),
            'create' => CreateEa::route('/create'),
            'view' => ViewEa::route('/{record}'),
            'edit' => EditEa::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
