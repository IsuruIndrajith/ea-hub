<?php

namespace App\Filament\Resources\Eas\Pages;

use App\Actions\GenerateEaContent;
use App\Filament\Resources\Eas\EaResource;
use App\Filament\Resources\ProductPages\ProductPageResource;
use App\Filament\Resources\SetReviews\SetReviewResource;
use App\Models\ProductPage;
use App\Models\SetReview;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditEa extends EditRecord
{
    protected static string $resource = EaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            Action::make('product_page')
                ->label('Product Page')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color('gray')
                ->action(function (): void {
                    $page = $this->record->productPage
                        ?? ProductPage::create(['ea_id' => $this->record->id, 'status' => 'draft']);
                    $this->redirect(ProductPageResource::getUrl('edit', ['record' => $page]));
                }),
            Action::make('set_review')
                ->label('Set Review')
                ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                ->color('gray')
                ->action(function (): void {
                    $review = $this->record->setReview
                        ?? SetReview::create(['ea_id' => $this->record->id]);
                    $this->redirect(SetReviewResource::getUrl('edit', ['record' => $review]));
                }),
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
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
