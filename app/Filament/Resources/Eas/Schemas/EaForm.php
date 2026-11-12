<?php

namespace App\Filament\Resources\Eas\Schemas;

use App\Models\EaSignal;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class EaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('EA details')
                    ->description('Only the EA name is required. Add the other details when they are available.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->required()->maxLength(255)->live(onBlur: true)
                            ->rules(function (Get $get, ?Model $record): array {
                                $rule = Rule::unique('eas', 'name')->where('version', $get('version'));

                                return [$record ? $rule->ignore($record->getKey()) : $rule];
                            }),
                        TextInput::make('version')->nullable()->maxLength(255)->live(onBlur: true),
                        TextInput::make('vendor_url')->label('Vendor site URL')->nullable()->url()->maxLength(255),
                        TextInput::make('platform')->placeholder('MT4, MT5, or Both'),
                        TextInput::make('product_page_url')->url(),
                        TextInput::make('developer_name'),
                        TextInput::make('developer_profile_link')->label('Developer profile URL')->nullable()->url()->maxLength(255),
                        TagsInput::make('primary_keywords')->nullable()->rules(['nullable', 'array'])->columnSpanFull(),
                        TagsInput::make('secondary_keywords')->columnSpanFull(),
                    ]),
                Section::make('Signals')->schema([
                    Repeater::make('signals')->relationship()->deletable(fn () => auth()->user()?->hasRole('admin') ?? false)->schema([
                        Select::make('type')->options(['mql5' => 'MQL5', 'myfxbook' => 'MyFxBook'])->required(),
                        TextInput::make('url')->url()->required()->rules([
                            function (Get $get) {
                                return function (string $attribute, mixed $value, $fail) use ($get): void {
                                    $host = strtolower((string) parse_url((string) $value, PHP_URL_HOST));
                                    $domain = $get('type') === EaSignal::TYPE_MQL5 ? 'mql5.com' : 'myfxbook.com';
                                    if ($host !== $domain && ! str_ends_with($host, '.'.$domain)) {
                                        $fail('The URL must belong to '.$domain.'.');
                                    }
                                };
                            },
                        ])->columnSpanFull(),
                        Textarea::make('notes')->rows(2)->columnSpanFull(),
                    ])->columns(2)->columnSpanFull(),
                ]),
                Section::make('Signal evidence')->columns(2)->schema([
                    SpatieMediaLibraryFileUpload::make('live_signal_images')->collection('live_signal_images')->multiple()->reorderable()->deletable(fn () => auth()->user()?->hasRole('admin') ?? false)->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->maxSize(5120),
                    SpatieMediaLibraryFileUpload::make('myfxbook_review_images')->collection('myfxbook_review_images')->multiple()->reorderable()->deletable(fn () => auth()->user()?->hasRole('admin') ?? false)->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->maxSize(5120),
                    SpatieMediaLibraryFileUpload::make('other_result_images')->collection('other_result_images')->label('Other result images')->multiple()->reorderable()->deletable(fn () => auth()->user()?->hasRole('admin') ?? false)->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->maxSize(5120)->columnSpanFull(),
                ]),
            ]);
    }
}
