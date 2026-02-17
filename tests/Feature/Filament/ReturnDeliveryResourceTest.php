<?php

declare(strict_types=1);

use App\Enums\ProductCondition;
use App\Enums\ReturnReason;
use App\Enums\ReturnStatus;
use App\Enums\ReturnType;
use App\Filament\Resources\ReturnDeliveries\Pages\CreateReturnDelivery;
use App\Filament\Resources\ReturnDeliveries\Pages\EditReturnDelivery;
use App\Filament\Resources\ReturnDeliveries\Pages\ListReturnDeliveries;
use App\Filament\Resources\ReturnDeliveries\Pages\ViewReturnDelivery;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ReturnDelivery;
use App\Models\User;
use App\Models\Warehouse;
use Filament\Forms\Components\Repeater;
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

it('can render the create page', function (): void {
    Livewire::test(CreateReturnDelivery::class)
        ->assertOk();
});

it('can create a return delivery', function (): void {
    $undoRepeaterFake = Repeater::fake();

    $warehouse = Warehouse::factory()
        ->recycle($this->user->team)
        ->create();

    $employee = Employee::factory()
        ->recycle($this->user->team)
        ->create();

    $product = Product::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(CreateReturnDelivery::class)
        ->fillForm([
            'return_number' => 'RET-TEST-001',
            'type' => ReturnType::CUSTOMER_RETURN->value,
            'warehouse_id' => $warehouse->id,
            'return_date' => now()->format('Y-m-d'),
            'processed_by' => $employee->id,
            'status' => ReturnStatus::DRAFT->value,
            'reason' => ReturnReason::DAMAGED->value,
            'returnDeliveryLines' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'unit_price' => 100,
                    'condition' => ProductCondition::GOOD->value,
                    'return_reason' => ReturnReason::DAMAGED->value,
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(ReturnDelivery::class, [
        'return_number' => 'RET-TEST-001',
        'type' => ReturnType::CUSTOMER_RETURN->value,
        'warehouse_id' => $warehouse->id,
        'team_id' => $this->user->team_id,
    ]);

    $undoRepeaterFake();
});

it('validates required fields on create', function (): void {
    Livewire::test(CreateReturnDelivery::class)
        ->fillForm([
            'return_number' => null,
            'type' => null,
            'warehouse_id' => null,
            'return_date' => null,
            'processed_by' => null,
            'status' => null,
            'reason' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'return_number' => 'required',
            'type' => 'required',
            'warehouse_id' => 'required',
            'return_date' => 'required',
            'processed_by' => 'required',
            'status' => 'required',
            'reason' => 'required',
        ]);
});

it('can delete a return delivery', function (): void {
    $returnDelivery = ReturnDelivery::factory()
        ->recycle($this->user->team)
        ->customerReturn()
        ->draft()
        ->create();

    Livewire::test(EditReturnDelivery::class, ['record' => $returnDelivery->getRouteKey()])
        ->callAction('delete');

    $this->assertSoftDeleted('return_deliveries', [
        'id' => $returnDelivery->id,
    ]);
});
