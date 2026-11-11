<?php

namespace App\Filament\Resources\SetReviews\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SetFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'setFiles';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('purpose'),
                Textarea::make('values')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                Textarea::make('groups')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                Textarea::make('risk_params')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                Textarea::make('differences'), Textarea::make('limitations'),
                Repeater::make('parameters')->relationship()->deletable(fn () => auth()->user()?->hasRole('admin') ?? false)->schema([
                    TextInput::make('name')->required(), TextInput::make('value'),
                    Select::make('group')->options(['lot' => 'Lot', 'risk' => 'Risk', 'entry' => 'Entry', 'sl_tp' => 'SL/TP', 'trade_mgmt' => 'Trade management', 'grid' => 'Grid', 'filters' => 'Filters']),
                    Select::make('classification')->options(['documented' => 'Documented', 'verified' => 'Verified', 'inferred' => 'Inferred', 'unknown' => 'Unknown']),
                    Textarea::make('explanation'), Textarea::make('practical_importance'),
                    Select::make('risk_impact')->options(['direct' => 'Direct', 'indirect' => 'Indirect', 'none' => 'None']),
                    Textarea::make('interacts_with')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Select::make('modifiable')->options(['safe' => 'Safe', 'keep_default' => 'Keep default', 'needs_expertise' => 'Needs expertise', 'unclear' => 'Unclear']),
                    TextInput::make('sort_order')->numeric()->default(0),
                ])->columns(2)->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->authorize(fn () => auth()->user()?->hasRole('admin') ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->authorize(fn () => auth()->user()?->hasRole('admin') ?? false),
                ]),
            ]);
    }
}
