<?php

namespace App\Filament\Resources\ProductPages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Source and SEO')->columns(2)->schema([
                    Select::make('ea_id')->relationship('ea', 'name')->searchable()->preload()->required()->unique(ignoreRecord: true),
                    TextInput::make('reviewed_by_author_name'),
                    TextInput::make('reviewed_by_author_profile_link')->url(),
                    TagsInput::make('input_keywords'),
                    TagsInput::make('secondary_keywords'),
                    TextInput::make('focus_keyword'), TextInput::make('rank_math_focus_keyword'),
                    TextInput::make('meta_title_1'), TextInput::make('meta_title_2'), TextInput::make('meta_title_3'),
                    Textarea::make('meta_description'), Textarea::make('short_description'),
                    Textarea::make('input_contents')->columnSpanFull(),
                ]),
                Section::make('Product description')->columns(2)->schema([
                    Textarea::make('intro')->columnSpanFull(), Textarea::make('first_hand_test_results')->columnSpanFull(),
                    Textarea::make('key_features')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('why_buy_this')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('honest_limitation'), Textarea::make('why_different'),
                    Textarea::make('strategy_logic')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('risk_controls')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('risk_disclaimer_line'),
                    Textarea::make('optimized_settings_table')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('install_steps')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('author_info'), Textarea::make('is_it_worth_it'), Textarea::make('telegram_support_line'),
                    Textarea::make('reviewed_by_closer'), Textarea::make('related_categories_paragraph'),
                ]),
                Section::make('CSV output')->columns(2)->schema([
                    TextInput::make('csv_name'), TextInput::make('csv_published'), Textarea::make('csv_short_description'),
                    Textarea::make('csv_description'), Textarea::make('csv_meta_tg_message'), Textarea::make('csv_meta_faq'),
                    Select::make('status')->options(['draft' => 'Draft', 'generated' => 'Generated', 'published' => 'Published'])->required()->default('draft'),
                ]),
            ]);
    }
}
