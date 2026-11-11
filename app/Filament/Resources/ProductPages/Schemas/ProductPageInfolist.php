<?php

namespace App\Filament\Resources\ProductPages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductPageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('ea.name')->label('EA'), TextEntry::make('focus_keyword'),
                TextEntry::make('secondary_keywords')->badge(), TextEntry::make('meta_title_1'),
                TextEntry::make('meta_description')->columnSpanFull(), TextEntry::make('intro')->columnSpanFull(),
                TextEntry::make('status')->badge(), TextEntry::make('updated_at')->dateTime(),
            ]);
    }
}
