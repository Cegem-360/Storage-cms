<?php

declare(strict_types=1);

namespace App\Livewire\Pages\ReturnDeliveries;

use App\Enums\ReturnStatus;
use App\Filament\Resources\ReturnDeliveries\Schemas\ReturnDeliveryInfolist;
use App\Livewire\Pages\Concerns\HasViewInfolist;
use App\Models\ReturnDelivery;
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
final class ViewReturnDelivery extends Component implements HasActions, HasSchemas
{
    use HasViewInfolist;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function approveAction(): Action
    {
        return Action::make('approve')
            ->label(__('Approve'))
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn (): bool => $this->record->status === ReturnStatus::INSPECTED)
            ->action(function (): void {
                $this->record->approve();

                Notification::make()
                    ->success()
                    ->title(__('Return approved'))
                    ->send();
            });
    }

    public function rejectAction(): Action
    {
        return Action::make('reject')
            ->label(__('Reject'))
            ->icon(Heroicon::OutlinedXCircle)
            ->color('danger')
            ->requiresConfirmation()
            ->visible(fn (): bool => in_array($this->record->status, [ReturnStatus::PENDING_INSPECTION, ReturnStatus::INSPECTED]))
            ->action(function (): void {
                $this->record->reject();

                Notification::make()
                    ->success()
                    ->title(__('Return rejected'))
                    ->send();
            });
    }

    public function restockAction(): Action
    {
        return Action::make('restock')
            ->label(__('Restock'))
            ->icon(Heroicon::OutlinedArrowPath)
            ->color('info')
            ->requiresConfirmation()
            ->visible(fn (): bool => $this->record->status === ReturnStatus::APPROVED && $this->record->isCustomerReturn())
            ->action(function (): void {
                $this->record->restock();

                Notification::make()
                    ->success()
                    ->title(__('Items restocked'))
                    ->body(__('Stock levels have been updated.'))
                    ->send();
            });
    }

    public function render(): View
    {
        return view('livewire.pages.return-deliveries.view-return-delivery');
    }

    protected static function getModel(): string
    {
        return ReturnDelivery::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.return-deliveries';
    }

    protected static function getResourceLabel(): string
    {
        return 'Return Delivery';
    }

    protected static function getInfolistSchemaClass(): string
    {
        return ReturnDeliveryInfolist::class;
    }

    /** @return Builder<ReturnDelivery> */
    protected function getRecordQuery(): Builder
    {
        return ReturnDelivery::query()->with(['returnDeliveryLines.product', 'order', 'warehouse']);
    }
}
