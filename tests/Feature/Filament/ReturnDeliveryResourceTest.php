<?php

declare(strict_types=1);

use App\Enums\ReturnReason;
use App\Enums\ReturnStatus;
use App\Filament\Resources\ReturnDeliveries\Pages\EditReturnDelivery;
use App\Filament\Resources\ReturnDeliveries\Pages\ListReturnDeliveries;
use App\Filament\Resources\ReturnDeliveries\Pages\ViewReturnDelivery;
use App\Models\Employee;
use App\Models\ReturnDelivery;
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
    Livewire::test(ListReturnDeliveries::class)
        ->assertOk();
});

it('can list return deliveries', function (): void {
    $returnDeliveries = ReturnDelivery::factory()
        ->count(3)
        ->recycle($this->user->team)
        ->customerReturn()
        ->create();

    Livewire::test(ListReturnDeliveries::class)
        ->assertCanSeeTableRecords($returnDeliveries);
});

it('can render the view page', function (): void {
    $returnDelivery = ReturnDelivery::factory()
        ->recycle($this->user->team)
        ->customerReturn()
        ->draft()
        ->create();

    Livewire::test(ViewReturnDelivery::class, ['record' => $returnDelivery->getRouteKey()])
        ->assertOk();
});

it('can render the edit page', function (): void {
    $returnDelivery = ReturnDelivery::factory()
        ->recycle($this->user->team)
        ->customerReturn()
        ->draft()
        ->create();

    Livewire::test(EditReturnDelivery::class, ['record' => $returnDelivery->getRouteKey()])
        ->assertOk();
});

it('can update a return delivery', function (): void {
    $returnDelivery = ReturnDelivery::factory()
        ->recycle($this->user->team)
        ->customerReturn()
        ->draft()
        ->create();

    $warehouse = Warehouse::factory()
        ->recycle($this->user->team)
        ->create();

    $employee = Employee::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditReturnDelivery::class, ['record' => $returnDelivery->getRouteKey()])
        ->fillForm([
            'warehouse_id' => $warehouse->id,
            'processed_by' => $employee->id,
            'status' => ReturnStatus::PENDING_INSPECTION->value,
            'reason' => ReturnReason::DAMAGED->value,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(ReturnDelivery::class, [
        'id' => $returnDelivery->id,
        'warehouse_id' => $warehouse->id,
        'processed_by' => $employee->id,
        'status' => ReturnStatus::PENDING_INSPECTION->value,
        'reason' => ReturnReason::DAMAGED->value,
    ]);
});

it('can list supplier return deliveries', function (): void {
    $supplierReturns = ReturnDelivery::factory()
        ->count(2)
        ->recycle($this->user->team)
        ->supplierReturn()
        ->create();

    Livewire::test(ListReturnDeliveries::class)
        ->assertCanSeeTableRecords($supplierReturns);
});
