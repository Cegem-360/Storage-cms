<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Inventories;

use App\Enums\InventoryStatus;
use App\Filament\Resources\Inventories\Schemas\InventoryForm;
use App\Livewire\Pages\Concerns\HasEditForm;
use App\Models\Inventory;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditInventory extends Component implements HasActions, HasSchemas
{
    use HasEditForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function applyCorrectionsAction(): Action
    {
        return Action::make('applyCorrections')
            ->label(__('Apply Corrections'))
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading(__('Apply Inventory Corrections'))
            ->modalDescription(__('This will update stock levels to match the actual counted quantities. This action cannot be undone.'))
            ->modalSubmitActionLabel(__('Apply Corrections'))
            ->visible(fn (): bool => $this->record->status === InventoryStatus::COMPLETED)
            ->action(function (): void {
                $this->record->load('inventoryLines');
                $this->record->applyCorrections();

                Notification::make()
                    ->success()
                    ->title(__('Corrections Applied'))
                    ->body(__('Stock levels have been updated to match the inventory count.'))
                    ->send();
            });
    }

    public function exportPdfAction(): Action
    {
        return Action::make('exportPdf')
            ->label(__('Print Inventory Sheet'))
            ->icon(Heroicon::OutlinedDocumentArrowDown)
            ->color('gray')
            ->action(function () {
                $this->record->load(['inventoryLines.product', 'warehouse']);

                $pdf = Pdf::loadView('pdf.inventory-count-sheet', [
                    'inventory' => $this->record,
                ]);

                return response()->streamDownload(
                    fn () => print ($pdf->output()),
                    "inventory-{$this->record->inventory_number}.pdf"
                );
            });
    }

    public function render(): View
    {
        return view('livewire.pages.inventories.edit-inventory');
    }

    protected static function getModel(): string
    {
        return Inventory::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return InventoryForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.inventories';
    }

    protected static function getResourceLabel(): string
    {
        return 'Inventory';
    }

    /** @return Builder<Inventory> */
    protected function getRecordQuery(): Builder
    {
        return Inventory::query()->with(['inventoryLines.product', 'warehouse']);
    }
}
