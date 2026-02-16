<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Services\AutoReorderService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Override;

final class OrderSuggestionWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('Order Suggestions');
    }

    public function getDescription(): ?string
    {
        return __('Products below reorder point');
    }

    #[Override]
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->with(['stocks', 'supplier'])
                    ->whereHas('stocks')
                    ->whereRaw(
                        '(SELECT COALESCE(SUM(stocks.quantity), 0) FROM stocks WHERE stocks.product_id = products.id AND stocks.deleted_at IS NULL) <= products.reorder_point'
                    )
            )
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('current_stock')
                    ->label('Current Stock')
                    ->state(fn (Product $record): int => $record->getTotalStock())
                    ->numeric()
                    ->color('danger'),

                TextColumn::make('reorder_point')
                    ->label('Reorder Point')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('suggested_qty')
                    ->label('Suggested Qty')
                    ->state(fn (Product $record): int => $record->calculateReorderQuantity())
                    ->numeric()
                    ->color('primary')
                    ->weight('bold'),

                TextColumn::make('supplier.company_name')
                    ->label('Supplier')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('create_order')
                    ->label('Create Order')
                    ->icon(Heroicon::OutlinedShoppingCart)
                    ->color('primary')
                    ->action(function (Product $record): void {
                        $team = auth()->user()->team;
                        $order = app(AutoReorderService::class)->createDraftOrder($record, $team);

                        if ($order) {
                            Notification::make()
                                ->title(__('Order created'))
                                ->body(__('Draft purchase order created for').' '.$record->name)
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title(__('Order not created'))
                                ->body(__('A purchase order already exists or supplier is missing for').' '.$record->name)
                                ->warning()
                                ->send();
                        }
                    }),
            ])
            ->paginated([5, 10, 25])
            ->emptyStateHeading(__('No products need reordering'))
            ->emptyStateDescription(__('All products are above their reorder points.'))
            ->emptyStateIcon(Heroicon::OutlinedCheckCircle);
    }
}
