<?php

declare(strict_types=1);

namespace App\Filament\Resources\Receipts\Pages;

use App\Enums\ReceiptStatus;
use App\Filament\Resources\Receipts\ReceiptResource;
use App\Services\BillingoService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Override;

final class EditReceipt extends EditRecord
{
    protected static string $resource = ReceiptResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateBillingoInvoice')
                ->label(__('Generate Invoice'))
                ->icon(Heroicon::DocumentText)
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(__('Generate Billingo Invoice'))
                ->modalDescription(__('This will create an invoice in Billingo for this receipt.'))
                ->visible(fn () => $this->record->status === ReceiptStatus::COMPLETED
                    && (bool) auth()->user()->team->getSetting('billingo_enabled', false))
                ->action(function (BillingoService $billingoService): void {
                    $result = $billingoService->createInvoiceFromReceipt(
                        $this->record,
                        auth()->user()->team,
                    );

                    if ($result['success']) {
                        Notification::make()
                            ->success()
                            ->title($result['message'])
                            ->send();
                    } else {
                        Notification::make()
                            ->danger()
                            ->title($result['message'])
                            ->send();
                    }
                }),
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
