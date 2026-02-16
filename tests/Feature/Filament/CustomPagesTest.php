<?php

declare(strict_types=1);

use App\Filament\Pages\AbcAnalysisReport;
use App\Filament\Pages\EditProfile;
use App\Filament\Pages\ExpectedStockArrivals;
use App\Filament\Pages\InventoryValuationReport;
use App\Filament\Pages\Settings;
use App\Filament\Pages\SupplierPerformanceReport;
use App\Filament\Pages\WarehouseStockOverview;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Team;
use App\Models\TeamSetting;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Settings page', function (): void {
    it('can render', function (): void {
        Livewire::test(Settings::class)
            ->assertOk();
    });

    it('loads existing settings from database', function (): void {
        $team = $this->user->team;

        TeamSetting::factory()->for($team)->create(['key' => 'low_stock_threshold', 'value' => '25']);
        TeamSetting::factory()->for($team)->create(['key' => 'auto_reorder_enabled', 'value' => '1']);
        TeamSetting::factory()->for($team)->create(['key' => 'notification_email', 'value' => 'alerts@example.com']);

        Livewire::test(Settings::class)
            ->assertOk()
            ->assertFormSet([
                'low_stock_threshold' => 25,
                'auto_reorder_enabled' => true,
                'notification_email' => 'alerts@example.com',
            ]);
    });

    it('can save settings', function (): void {
        Livewire::test(Settings::class)
            ->fillForm([
                'low_stock_threshold' => 15,
                'auto_reorder_enabled' => true,
                'notification_email' => 'test@example.com',
            ])
            ->call('save')
            ->assertNotified();

        $team = $this->user->team;
        $team->load('settings');

        expect($team->getSetting('low_stock_threshold'))->toBe('15')
            ->and($team->getSetting('auto_reorder_enabled'))->toBe('1')
            ->and($team->getSetting('notification_email'))->toBe('test@example.com');
    });

    it('scopes settings per team', function (): void {
        $otherTeam = Team::factory()->create();
        $otherTeam->setSetting('low_stock_threshold', '99');

        $team = $this->user->team;
        $team->setSetting('low_stock_threshold', '20');

        Livewire::test(Settings::class)
            ->assertOk()
            ->assertFormSet([
                'low_stock_threshold' => 20,
            ]);

        $otherTeam->load('settings');
        expect($otherTeam->getSetting('low_stock_threshold'))->toBe('99');
    });

    it('validates low stock threshold is required', function (): void {
        Livewire::test(Settings::class)
            ->fillForm([
                'low_stock_threshold' => null,
            ])
            ->call('save')
            ->assertHasFormErrors(['low_stock_threshold' => 'required']);
    });

    it('validates notification email format', function (): void {
        Livewire::test(Settings::class)
            ->fillForm([
                'low_stock_threshold' => 10,
                'notification_email' => 'not-an-email',
            ])
            ->call('save')
            ->assertHasFormErrors(['notification_email' => 'email']);
    });
});

describe('EditProfile page', function (): void {
    it('can render', function (): void {
        Livewire::test(EditProfile::class)
            ->assertOk();
    });
});

describe('ExpectedStockArrivals page', function (): void {
    it('can render', function (): void {
        Livewire::test(ExpectedStockArrivals::class)
            ->assertOk();
    });

    it('shows confirmed purchase orders with delivery dates', function (): void {
        $team = $this->user->team;

        $visibleOrders = Order::factory()
            ->count(2)
            ->purchaseOrder()
            ->confirmed()
            ->recycle($team)
            ->create([
                'delivery_date' => now()->addDays(5),
            ]);

        $draftOrder = Order::factory()
            ->purchaseOrder()
            ->draft()
            ->recycle($team)
            ->create([
                'delivery_date' => now()->addDays(5),
            ]);

        Livewire::test(ExpectedStockArrivals::class)
            ->assertOk()
            ->assertCanSeeTableRecords($visibleOrders)
            ->assertCanNotSeeTableRecords([$draftOrder]);
    });

    it('does not show orders without delivery dates', function (): void {
        $team = $this->user->team;

        $orderWithoutDelivery = Order::factory()
            ->purchaseOrder()
            ->confirmed()
            ->recycle($team)
            ->create([
                'delivery_date' => null,
            ]);

        Livewire::test(ExpectedStockArrivals::class)
            ->assertOk()
            ->assertCanNotSeeTableRecords([$orderWithoutDelivery]);
    });
});

describe('InventoryValuationReport page', function (): void {
    it('can render', function (): void {
        Livewire::test(InventoryValuationReport::class)
            ->assertOk();
    });

    it('can render with warehouse data', function (): void {
        $team = $this->user->team;
        Warehouse::factory()->count(2)->recycle($team)->create();

        Livewire::test(InventoryValuationReport::class)
            ->assertOk();
    });

    it('can switch group by to product', function (): void {
        Livewire::test(InventoryValuationReport::class)
            ->set('groupBy', 'product')
            ->assertOk();
    });

    it('can switch group by to category', function (): void {
        Livewire::test(InventoryValuationReport::class)
            ->set('groupBy', 'category')
            ->assertOk();
    });
});

describe('WarehouseStockOverview page', function (): void {
    it('can render', function (): void {
        Livewire::test(WarehouseStockOverview::class)
            ->assertOk();
    });

    it('can render with stock data', function (): void {
        $team = $this->user->team;
        $warehouse = Warehouse::factory()->recycle($team)->create();
        $product = Product::factory()->recycle($team)->create();

        Stock::factory()->recycle($team)->create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 100,
        ]);

        Livewire::test(WarehouseStockOverview::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$product]);
    });
});

describe('SupplierPerformanceReport page', function (): void {
    it('can render', function (): void {
        Livewire::test(SupplierPerformanceReport::class)
            ->assertOk();
    });

    it('can render with supplier data', function (): void {
        $team = $this->user->team;
        $supplier = Supplier::factory()->recycle($team)->create(['is_active' => true]);

        Order::factory()
            ->count(3)
            ->purchaseOrder()
            ->recycle($team)
            ->create(['supplier_id' => $supplier->id]);

        Livewire::test(SupplierPerformanceReport::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$supplier]);
    });
});

describe('AbcAnalysisReport page', function (): void {
    it('can render', function (): void {
        Livewire::test(AbcAnalysisReport::class)
            ->assertOk();
    });

    it('can render with product stock data', function (): void {
        $team = $this->user->team;
        $warehouse = Warehouse::factory()->recycle($team)->create();

        $products = Product::factory()
            ->count(3)
            ->recycle($team)
            ->create();

        foreach ($products as $product) {
            Stock::factory()->recycle($team)->create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'quantity' => fake()->numberBetween(10, 200),
                'unit_cost' => fake()->randomFloat(2, 100, 5000),
            ]);
        }

        Livewire::test(AbcAnalysisReport::class)
            ->assertOk()
            ->assertCanSeeTableRecords($products);
    });
});
