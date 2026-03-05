<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderType;
use App\Models\Order;
use App\Models\Team;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Override;

final class RecentOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('Recent Purchase Orders');
    }

    #[Override]
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['supplier'])
                    ->where('type', OrderType::PURCHASE)
                    ->latest('order_date')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('Order'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('supplier.name')
                    ->label(__('Supplier'))
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                TextColumn::make('order_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('delivery_date')
                    ->label(__('Expected Delivery'))
                    ->date()
                    ->sortable()
                    ->placeholder(__('Not set')),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label(__('Total'))
                    ->money(Team::currency())
                    ->sortable(),
            ])
            ->paginated(false)
            ->emptyStateHeading(__('No recent orders'))
            ->emptyStateDescription(__('Create your first purchase order to see it here.'))
            ->emptyStateIcon(Heroicon::OutlinedShoppingCart);
    }
}
