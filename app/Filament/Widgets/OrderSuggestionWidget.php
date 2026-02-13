<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

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
                    ->label(__('SKU'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label(__('Product'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('current_stock')
                    ->label(__('Current Stock'))
                    ->state(fn (Product $record): int => $record->getTotalStock())
                    ->numeric()
                    ->color('danger'),

                TextColumn::make('reorder_point')
                    ->label(__('Reorder Point'))
                    ->numeric()
                    ->sortable(),

                TextColumn::make('suggested_qty')
                    ->label(__('Suggested Qty'))
                    ->state(fn (Product $record): int => $record->calculateReorderQuantity())
                    ->numeric()
                    ->color('primary')
                    ->weight('bold'),

                TextColumn::make('supplier.company_name')
                    ->label(__('Supplier'))
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('create_order')
                    ->label(__('Create Order'))
                    ->icon('heroicon-o-shopping-cart')
                    ->color('primary')
                    ->action(function (Product $record): void {
                        $order = Order::create([
                            'order_number' => 'PO-'.now()->format('Ymd').'-'.mb_str_pad((string) (Order::count() + 1), 4, '0', STR_PAD_LEFT),
                            'type' => OrderType::PURCHASE,
                            'supplier_id' => $record->supplier_id,
                            'status' => OrderStatus::DRAFT,
                            'order_date' => now(),
                            'total_amount' => 0,
                        ]);

                        $order->orderLines()->create([
                            'product_id' => $record->id,
                            'quantity' => $record->calculateReorderQuantity(),
                            'unit_price' => $record->standard_cost ?? $record->price ?? 0,
                            'discount_percent' => 0,
                        ]);

                        $order->refreshTotal();

                        Notification::make()
                            ->title(__('Order created'))
                            ->body(__('Draft purchase order created for').' '.$record->name)
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated([5, 10, 25])
            ->emptyStateHeading(__('No products need reordering'))
            ->emptyStateDescription(__('All products are above their reorder points.'))
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
