<?php

namespace App\Filament\Resources\Eas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'), TextEntry::make('version'), TextEntry::make('vendor_url')->url(),
                TextEntry::make('platform'), TextEntry::make('primary_keywords')->badge(),
                TextEntry::make('secondary_keywords')->badge(), TextEntry::make('signals.url')->label('Signals')->listWithLineBreaks(),
                TextEntry::make('creator.name')->label('Created by'), TextEntry::make('updater.name')->label('Updated by'),
                TextEntry::make('created_at')->dateTime(), TextEntry::make('updated_at')->dateTime(),
            ]);
    }
}
