<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('password')->password()->revealable()->required(fn (string $operation): bool => $operation === 'create')->dehydrated(fn ($state): bool => filled($state)),
                Select::make('roles')->relationship('roles', 'name')->multiple()->preload()->required(),
                Toggle::make('is_active')->label('Active')->default(true),
            ]);
    }
}
