<?php

namespace App\Filament\Resources\SetReviews\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SetReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('ea.name')->label('EA'), TextEntry::make('focus_keyword'),
                TextEntry::make('secondary_keywords')->badge(), TextEntry::make('recommended_meta_title'),
                TextEntry::make('seo_introduction')->columnSpanFull(), TextEntry::make('final_verdict')->columnSpanFull(),
                TextEntry::make('status')->badge(), TextEntry::make('updated_at')->dateTime(),
            ]);
    }
}
