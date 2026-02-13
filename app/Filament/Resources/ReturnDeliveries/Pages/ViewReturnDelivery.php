<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReturnDeliveries\Pages;

use App\Enums\ReturnStatus;
use App\Filament\Resources\ReturnDeliveries\ReturnDeliveryResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Override;

final class ViewReturnDelivery extends ViewRecord
{
    protected static string $resource = ReturnDeliveryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn ($record) => $record->status->isEditable()),

            Action::make('approve')
                ->label(__('Approve'))
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn ($record): bool => $record->status === ReturnStatus::INSPECTED)
                ->action(function ($record): void {
                    $record->approve();

                    Notification::make()
                        ->success()
                        ->title(__('Return approved'))
                        ->send();
                }),

            Action::make('reject')
                ->label(__('Reject'))
                ->icon(Heroicon::OutlinedXCircle)
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn ($record): bool => in_array($record->status, [ReturnStatus::PENDING_INSPECTION, ReturnStatus::INSPECTED]))
                ->action(function ($record): void {
                    $record->reject();

                    Notification::make()
                        ->success()
                        ->title(__('Return rejected'))
                        ->send();
                }),

            Action::make('restock')
                ->label(__('Restock'))
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('info')
                ->requiresConfirmation()
                ->visible(fn ($record): bool => $record->status === ReturnStatus::APPROVED && $record->isCustomerReturn())
                ->action(function ($record): void {
                    $record->restock();

                    Notification::make()
                        ->success()
                        ->title(__('Items restocked'))
                        ->body(__('Stock levels have been updated.'))
                        ->send();
                }),
        ];
    }
}
