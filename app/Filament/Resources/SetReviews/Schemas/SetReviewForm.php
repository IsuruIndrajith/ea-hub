<?php

namespace App\Filament\Resources\SetReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SetReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('EA and source evidence')->columns(2)->schema([
                    Select::make('ea_id')->relationship('ea', 'name')->searchable()->preload()->required()->unique(ignoreRecord: true),
                    Textarea::make('vendor_docs'), Textarea::make('config_notes'),
                    TextInput::make('focus_keyword'), TagsInput::make('secondary_keywords'), TextInput::make('search_intent'),
                    TextInput::make('meta_title_1'), TextInput::make('meta_title_2'), TextInput::make('meta_title_3'),
                    TextInput::make('recommended_meta_title'), Textarea::make('meta_description'),
                    TextInput::make('recommended_slug'), TextInput::make('suggested_h1'),
                ]),
                Section::make('Review content')->columns(2)->schema([
                    Textarea::make('seo_introduction')->columnSpanFull(),
                    Textarea::make('specs_table')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('set_comparison_table')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('parameter_classification_summary')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('important_parameters')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('requirements_table')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('load_set_steps')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('customization_guidance')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('customization_cautions')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('config_strengths_limitations')->columnSpanFull(),
                    Textarea::make('who_should_use')->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(fn ($state) => $state ? json_decode($state, true, 512, JSON_THROW_ON_ERROR) : null)->rules(['nullable', 'json']),
                    Textarea::make('final_verdict')->columnSpanFull(), Textarea::make('product_page_cta'),
                    Textarea::make('review_methodology'), Textarea::make('risk_disclaimer'), Textarea::make('editorial_notes'),
                    Select::make('status')->options(['draft' => 'Draft', 'generated' => 'Generated', 'published' => 'Published'])->required()->default('draft'),
                ]),
            ]);
    }
}
