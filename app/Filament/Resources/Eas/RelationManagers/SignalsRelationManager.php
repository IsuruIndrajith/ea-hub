<?php

namespace App\Filament\Resources\Eas\RelationManagers;

use App\Models\EaSignal;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SignalsRelationManager extends RelationManager
{
    protected static string $relationship = 'signals';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')->options(['mql5' => 'MQL5', 'myfxbook' => 'MyFxBook'])->required(),
                TextInput::make('url')->url()->required()->rules([function ($get) {
                    return function (string $attribute, mixed $value, $fail) use ($get): void {
                        $host = strtolower((string) parse_url((string) $value, PHP_URL_HOST));
                        $domain = $get('type') === EaSignal::TYPE_MQL5 ? 'mql5.com' : 'myfxbook.com';
                        if ($host !== $domain && ! str_ends_with($host, '.'.$domain)) {
                            $fail('The URL must belong to '.$domain.'.');
                        }
                    };
                }]),
                Textarea::make('notes')->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('url')->limit(50),
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
