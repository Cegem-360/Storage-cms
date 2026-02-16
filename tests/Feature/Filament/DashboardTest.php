<?php

declare(strict_types=1);

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\LowStockWidget;
use App\Filament\Widgets\OrderSuggestionWidget;
use App\Filament\Widgets\RecentOrdersWidget;
use App\Filament\Widgets\StockMovementChartWidget;
use App\Filament\Widgets\StockStatsWidget;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Dashboard', function (): void {
    it('can render the dashboard page', function (): void {
        Livewire::test(Dashboard::class)
            ->assertOk();
    });

    it('registers all expected widgets', function (): void {
        $dashboard = new Dashboard();
        $widgets = $dashboard->getWidgets();

        expect($widgets)->toContain(StockStatsWidget::class)
            ->toContain(StockMovementChartWidget::class)
            ->toContain(LowStockWidget::class)
            ->toContain(RecentOrdersWidget::class)
            ->toContain(OrderSuggestionWidget::class)
            ->toHaveCount(5);
    });
});

describe('StockStatsWidget', function (): void {
    it('can render', function (): void {
        Livewire::test(StockStatsWidget::class)
            ->assertOk();
    });

    it('displays stats with data', function (): void {
        $team = $this->user->team;

        Product::factory()->count(3)->recycle($team)->create();
        $warehouse = Warehouse::factory()->recycle($team)->create();
        Stock::factory()->count(2)->recycle($team)->recycle($warehouse)->create([
            'quantity' => 50,
            'minimum_stock' => 10,
        ]);
        Stock::factory()->recycle($team)->recycle($warehouse)->lowStock()->create();

        Livewire::test(StockStatsWidget::class)
            ->assertOk();
    });
});

describe('StockMovementChartWidget', function (): void {
    it('can render', function (): void {
        Livewire::test(StockMovementChartWidget::class)
            ->assertOk();
    });
});

describe('LowStockWidget', function (): void {
    it('can render', function (): void {
        Livewire::test(LowStockWidget::class)
            ->assertOk();
    });

    it('displays low stock items', function (): void {
        $team = $this->user->team;
        $warehouse = Warehouse::factory()->recycle($team)->create();

        $lowStocks = Stock::factory()
            ->count(3)
            ->recycle($team)
            ->recycle($warehouse)
            ->lowStock()
            ->create();

        Livewire::test(LowStockWidget::class)
            ->assertOk()
            ->assertCanSeeTableRecords($lowStocks);
    });

    it('does not show stocks above minimum', function (): void {
        $team = $this->user->team;
        $warehouse = Warehouse::factory()->recycle($team)->create();

        $healthyStock = Stock::factory()
            ->recycle($team)
            ->recycle($warehouse)
            ->create([
                'quantity' => 100,
                'minimum_stock' => 10,
            ]);

        Livewire::test(LowStockWidget::class)
            ->assertOk()
            ->assertCanNotSeeTableRecords([$healthyStock]);
    });
});

describe('RecentOrdersWidget', function (): void {
    it('can render', function (): void {
        Livewire::test(RecentOrdersWidget::class)
            ->assertOk();
    });

    it('displays recent purchase orders', function (): void {
        $team = $this->user->team;

        $orders = Order::factory()
            ->count(3)
            ->purchaseOrder()
            ->recycle($team)
            ->create();

        Livewire::test(RecentOrdersWidget::class)
            ->assertOk()
            ->assertCanSeeTableRecords($orders);
    });
});

describe('OrderSuggestionWidget', function (): void {
    it('can render', function (): void {
        Livewire::test(OrderSuggestionWidget::class)
            ->assertOk();
    });

    it('displays products needing reorder', function (): void {
        $team = $this->user->team;
        $warehouse = Warehouse::factory()->recycle($team)->create();

        $product = Product::factory()->recycle($team)->create([
            'reorder_point' => 50,
        ]);

        Stock::factory()->recycle($team)->recycle($warehouse)->create([
            'product_id' => $product->id,
            'quantity' => 5,
            'minimum_stock' => 10,
        ]);

        Livewire::test(OrderSuggestionWidget::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$product]);
    });
});
