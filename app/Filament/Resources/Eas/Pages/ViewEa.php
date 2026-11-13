<?php

namespace App\Filament\Resources\Eas\Pages;

use App\Actions\GenerateEaContent;
use App\Actions\PublishToStore;
use App\Filament\Resources\Eas\EaResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewEa extends ViewRecord
{
    protected static string $resource = EaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            Action::make('generate_content')
                ->label('Generate Content')
                ->icon(Heroicon::OutlinedSparkles)
                ->color('success')
                ->form([
                    Select::make('type')
                        ->label('Content type')
                        ->options([
                            'product_page' => 'Product Page',
                            'set_review'   => 'Set Review',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    try {
                        app(GenerateEaContent::class)->run($this->record, $data['type']);
                        Notification::make()->title('Content generated successfully.')->success()->send();
                    } catch (\Throwable $e) {
                        Notification::make()->title('Generation failed')->body($e->getMessage())->danger()->send();
                    }
                }),

            Action::make('publish_to_store')
                ->label('Publish to Store')
                ->icon(Heroicon::OutlinedArrowUpTray)
                ->color('warning')
                ->requiresConfirmation(false)
                ->form([
                    TextInput::make('price_cents')
                        ->label('Original price (cents)')
                        ->helperText('e.g. 84900 = $849.00')
                        ->numeric()
                        ->required()
                        ->minValue(0),
                    TextInput::make('sale_price_cents')
                        ->label('Sale price (cents)')
                        ->helperText('e.g. 4999 = $49.99')
                        ->numeric()
                        ->required()
                        ->minValue(0),
                    TextInput::make('hue')
                        ->label('Card colour hue (0–360)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(360)
                        ->default(60),
                    Toggle::make('is_active')
                        ->label('List as active / visible')
                        ->default(true),
                ])
                ->action(function (array $data): void {
                    $isActive = (bool) $data['is_active'];
                    try {
                        app(PublishToStore::class)->run($this->record, [
                            'price_cents'      => (int) $data['price_cents'],
                            'sale_price_cents' => (int) $data['sale_price_cents'],
                            'hue'              => (int) $data['hue'],
                            'is_active'        => $isActive,
                        ]);

                        $storeUrl  = rtrim(config('services.ea_store.url', env('STORE_API_URL', '')), '/');
                        $token     = env('INTERNAL_API_KEY', '');
                        $previewUrl = "{$storeUrl}/product/{$this->record->slug}?preview_token={$token}";

                        Notification::make()
                            ->title($isActive ? 'Published to EA Store' : 'Saved as draft in EA Store')
                            ->body($isActive
                                ? "\"{$this->record->name}\" is now live."
                                : "Draft saved. <a href=\"{$previewUrl}\" target=\"_blank\" class=\"underline\">Preview it here</a>."
                            )
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Publish failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('preview_in_store')
                ->label('Preview in Store')
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->url(function (): string {
                    $storeUrl = rtrim(config('services.ea_store.url', env('STORE_API_URL', '')), '/');
                    $token    = env('INTERNAL_API_KEY', '');
                    return "{$storeUrl}/product/{$this->record->slug}?preview_token={$token}";
                })
                ->openUrlInNewTab(),
        ];
    }
}
