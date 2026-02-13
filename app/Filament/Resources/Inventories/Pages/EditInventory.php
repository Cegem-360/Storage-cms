<?php

declare(strict_types=1);

namespace App\Filament\Resources\Inventories\Pages;

use App\Enums\InventoryStatus;
use App\Filament\Resources\Inventories\InventoryResource;
use App\Models\Inventory;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Override;

final class EditInventory extends EditRecord
{
    protected static string $resource = InventoryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('applyCorrections')
                ->label('Apply Corrections')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(__('Apply Inventory Corrections'))
                ->modalDescription(__('This will update stock levels to match the actual counted quantities. This action cannot be undone.'))
                ->modalSubmitActionLabel(__('Apply Corrections'))
                ->action(function (): void {
                    /** @var Inventory $record */
                    $record = $this->getRecord();
                    $record->load('inventoryLines');
                    $record->applyCorrections();

                    Notification::make()
                        ->success()
                        ->title(__('Corrections Applied'))
                        ->body(__('Stock levels have been updated to match the inventory count.'))
                        ->send();

                    $this->redirect(InventoryResource::getUrl('edit', ['record' => $record]));
                })
                ->visible(fn (): bool => $this->getRecord()->status === InventoryStatus::COMPLETED),

            Action::make('exportPdf')
                ->label('Print Inventory Sheet')
                ->icon(Heroicon::OutlinedDocumentArrowDown)
                ->color('gray')
                ->action(function () {
                    /** @var Inventory $inventory */
                    $inventory = $this->getRecord()->load('inventoryLines.product', 'warehouse');
                    $pdf = Pdf::loadView('pdf.inventory-count-sheet', [
                        'inventory' => $inventory,
                    ]);

                    return response()->streamDownload(
                        fn () => print ($pdf->output()),
                        "inventory-{$inventory->inventory_number}.pdf"
                    );
                }),

            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
