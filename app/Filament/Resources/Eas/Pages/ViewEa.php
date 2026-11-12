<?php

namespace App\Filament\Resources\Eas\Pages;

use App\Actions\GenerateEaContent;
use App\Filament\Resources\Eas\EaResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
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
        ];
    }
}
