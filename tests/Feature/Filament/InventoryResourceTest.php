<?php

declare(strict_types=1);

use App\Enums\InventoryStatus;
use App\Enums\InventoryType;
use App\Filament\Resources\Inventories\Pages\CreateInventory;
use App\Filament\Resources\Inventories\Pages\EditInventory;
use App\Filament\Resources\Inventories\Pages\ListInventories;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render the list page', function (): void {
    Livewire::test(ListInventories::class)
        ->assertOk();
});

it('can list inventories', function (): void {
    $inventories = Inventory::factory()
        ->count(3)
        ->recycle($this->user->team)
        ->create();

    Livewire::test(ListInventories::class)
        ->assertCanSeeTableRecords($inventories);
});

it('can render the create page', function (): void {
    Livewire::test(CreateInventory::class)
        ->assertOk();
});

it('can create an inventory', function (): void {
    $warehouse = Warehouse::factory()
        ->recycle($this->user->team)
        ->create();

    $employee = Employee::factory()
        ->recycle($this->user->team)
        ->create();

    $inventoryNumber = 'INV-TEST-'.fake()->unique()->numerify('######');

    Livewire::test(CreateInventory::class)
        ->fillForm([
            'inventory_number' => $inventoryNumber,
            'warehouse_id' => $warehouse->id,
            'conducted_by' => $employee->id,
            'inventory_date' => now()->format('Y-m-d'),
            'status' => InventoryStatus::IN_PROGRESS->value,
            'type' => InventoryType::FULL->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Inventory::class, [
        'inventory_number' => $inventoryNumber,
        'warehouse_id' => $warehouse->id,
        'conducted_by' => $employee->id,
        'status' => InventoryStatus::IN_PROGRESS->value,
        'type' => InventoryType::FULL->value,
    ]);
});

it('can validate required fields on create', function (): void {
    Livewire::test(CreateInventory::class)
        ->fillForm([
            'inventory_number' => null,
            'warehouse_id' => null,
            'conducted_by' => null,
            'inventory_date' => null,
            'type' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'inventory_number' => 'required',
            'warehouse_id' => 'required',
            'conducted_by' => 'required',
            'inventory_date' => 'required',
            'type' => 'required',
        ]);
});

it('can render the edit page', function (): void {
    $inventory = Inventory::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditInventory::class, ['record' => $inventory->getRouteKey()])
        ->assertOk();
});

it('can update an inventory', function (): void {
    $inventory = Inventory::factory()
        ->recycle($this->user->team)
        ->create();

    $newInventoryNumber = 'INV-UPDATED-'.fake()->unique()->numerify('######');
    $warehouse = Warehouse::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditInventory::class, ['record' => $inventory->getRouteKey()])
        ->fillForm([
            'inventory_number' => $newInventoryNumber,
            'warehouse_id' => $warehouse->id,
            'type' => InventoryType::CYCLE->value,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Inventory::class, [
        'id' => $inventory->id,
        'inventory_number' => $newInventoryNumber,
        'warehouse_id' => $warehouse->id,
        'type' => InventoryType::CYCLE->value,
    ]);
});

it('can delete an inventory', function (): void {
    $inventory = Inventory::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditInventory::class, ['record' => $inventory->getRouteKey()])
        ->callAction('delete');

    $this->assertSoftDeleted('inventories', [
        'id' => $inventory->id,
    ]);
});
