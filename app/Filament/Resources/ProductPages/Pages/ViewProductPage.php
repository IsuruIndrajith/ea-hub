<?php

namespace App\Filament\Resources\ProductPages\Pages;

use App\Actions\PublishToStore;
use App\Filament\Resources\ProductPages\ProductPageResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewProductPage extends ViewRecord
{
    protected static string $resource = ProductPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

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
                    $ea = $this->record->ea;

                    if (! $ea) {
                        Notification::make()
                            ->title('No EA linked')
                            ->body('This product page is not linked to an EA.')
                            ->danger()
                            ->send();

                        return;
                    }

                    try {
                        app(PublishToStore::class)->run($ea, [
                            'price_cents'      => (int) $data['price_cents'],
                            'sale_price_cents' => (int) $data['sale_price_cents'],
                            'hue'              => (int) $data['hue'],
                            'is_active'        => (bool) $data['is_active'],
                        ]);

                        Notification::make()
                            ->title('Published to EA Store')
                            ->body("\"{$ea->name}\" is now live on the store.")
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
        ];
    }
}
