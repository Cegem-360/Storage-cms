<?php

declare(strict_types=1);

use App\Enums\WarehouseType;
use App\Filament\Resources\Warehouses\Pages\CreateWarehouse;
use App\Filament\Resources\Warehouses\Pages\EditWarehouse;
use App\Filament\Resources\Warehouses\Pages\ListWarehouses;
use App\Filament\Resources\Warehouses\Pages\ViewWarehouse;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Warehouse Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListWarehouses::class)
            ->assertOk();
    });

    it('can list warehouses', function (): void {
        $warehouses = Warehouse::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListWarehouses::class)
            ->assertCanSeeTableRecords($warehouses);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateWarehouse::class)
            ->assertOk();
    });

    it('can create a warehouse', function (): void {
        Livewire::test(CreateWarehouse::class)
            ->fillForm([
                'code' => 'WH-MAIN',
                'name' => 'Main Warehouse',
                'type' => WarehouseType::MAIN->value,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('warehouses', [
            'code' => 'WH-MAIN',
            'name' => 'Main Warehouse',
            'type' => WarehouseType::MAIN->value,
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can render the edit page', function (): void {
        $warehouse = Warehouse::factory()->recycle($this->user->team)->create();

        Livewire::test(EditWarehouse::class, ['record' => $warehouse->getRouteKey()])
            ->assertOk();
    });

    it('can edit a warehouse', function (): void {
        $warehouse = Warehouse::factory()->recycle($this->user->team)->create();

        Livewire::test(EditWarehouse::class, ['record' => $warehouse->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Warehouse',
                'type' => WarehouseType::DISTRIBUTION->value,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($warehouse->fresh())
            ->name->toBe('Updated Warehouse')
            ->type->toBe(WarehouseType::DISTRIBUTION);
    });

    it('can render the view page', function (): void {
        $warehouse = Warehouse::factory()->recycle($this->user->team)->create();

        Livewire::test(ViewWarehouse::class, ['record' => $warehouse->getRouteKey()])
            ->assertOk();
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateWarehouse::class)
            ->fillForm([
                'code' => null,
                'name' => null,
                'type' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'code' => 'required',
                'name' => 'required',
                'type' => 'required',
            ]);
    });

    it('validates required fields on edit', function (): void {
        $warehouse = Warehouse::factory()->recycle($this->user->team)->create();

        Livewire::test(EditWarehouse::class, ['record' => $warehouse->getRouteKey()])
            ->fillForm([
                'code' => null,
                'name' => null,
                'type' => null,
            ])
            ->call('save')
            ->assertHasFormErrors([
                'code' => 'required',
                'name' => 'required',
                'type' => 'required',
            ]);
    });

    it('can delete a warehouse', function (): void {
        $warehouse = Warehouse::factory()->recycle($this->user->team)->create();

        Livewire::test(EditWarehouse::class, ['record' => $warehouse->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('warehouses', [
            'id' => $warehouse->id,
        ]);
    });
});
