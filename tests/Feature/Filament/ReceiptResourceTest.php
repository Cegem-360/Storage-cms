<?php

declare(strict_types=1);

use App\Enums\ReceiptStatus;
use App\Filament\Resources\Receipts\Pages\CreateReceipt;
use App\Filament\Resources\Receipts\Pages\EditReceipt;
use App\Filament\Resources\Receipts\Pages\ListReceipts;
use App\Filament\Resources\Receipts\Pages\ViewReceipt;
use App\Models\Employee;
use App\Models\Receipt;
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
    Livewire::test(ListReceipts::class)
        ->assertOk();
});

it('can list receipts', function (): void {
    $receipts = Receipt::factory()
        ->count(3)
        ->recycle($this->user->team)
        ->create();

    Livewire::test(ListReceipts::class)
        ->assertCanSeeTableRecords($receipts);
});

it('can render the create page', function (): void {
    Livewire::test(CreateReceipt::class)
        ->assertOk();
});

it('can create a receipt', function (): void {
    $warehouse = Warehouse::factory()
        ->recycle($this->user->team)
        ->create();

    $employee = Employee::factory()
        ->recycle($this->user->team)
        ->create();

    $receiptNumber = 'REC-TEST-'.fake()->unique()->numerify('######');

    Livewire::test(CreateReceipt::class)
        ->fillForm([
            'receipt_number' => $receiptNumber,
            'warehouse_id' => $warehouse->id,
            'received_by' => $employee->id,
            'receipt_date' => now()->format('Y-m-d'),
            'status' => ReceiptStatus::PENDING->value,
            'receiptLines' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Receipt::class, [
        'receipt_number' => $receiptNumber,
        'warehouse_id' => $warehouse->id,
        'received_by' => $employee->id,
        'status' => ReceiptStatus::PENDING->value,
    ]);
});

it('can validate required fields on create', function (): void {
    Livewire::test(CreateReceipt::class)
        ->fillForm([
            'receipt_number' => null,
            'warehouse_id' => null,
            'received_by' => null,
            'receipt_date' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'receipt_number' => 'required',
            'warehouse_id' => 'required',
            'received_by' => 'required',
            'receipt_date' => 'required',
        ]);
});

it('can render the edit page', function (): void {
    $receipt = Receipt::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditReceipt::class, ['record' => $receipt->getRouteKey()])
        ->assertOk();
});

it('can update a receipt', function (): void {
    $receipt = Receipt::factory()
        ->recycle($this->user->team)
        ->create();

    $newReceiptNumber = 'REC-UPDATED-'.fake()->unique()->numerify('######');

    Livewire::test(EditReceipt::class, ['record' => $receipt->getRouteKey()])
        ->fillForm([
            'receipt_number' => $newReceiptNumber,
            'status' => ReceiptStatus::IN_PROGRESS->value,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Receipt::class, [
        'id' => $receipt->id,
        'receipt_number' => $newReceiptNumber,
        'status' => ReceiptStatus::IN_PROGRESS->value,
    ]);
});

it('can render the view page', function (): void {
    $receipt = Receipt::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(ViewReceipt::class, ['record' => $receipt->getRouteKey()])
        ->assertOk();
});

it('can delete a receipt', function (): void {
    $receipt = Receipt::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditReceipt::class, ['record' => $receipt->getRouteKey()])
        ->callAction('delete');

    $this->assertSoftDeleted('receipts', [
        'id' => $receipt->id,
    ]);
});
