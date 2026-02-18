<?php

declare(strict_types=1);

use App\Enums\InvoiceStatus;
use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Filament\Resources\Invoices\Pages\EditInvoice;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Resources\Invoices\Pages\ViewInvoice;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render the list page', function (): void {
    Livewire::test(ListInvoices::class)
        ->assertOk();
});

it('can list invoices', function (): void {
    $invoices = Invoice::factory()
        ->count(3)
        ->recycle($this->user->team)
        ->create();

    Livewire::test(ListInvoices::class)
        ->assertCanSeeTableRecords($invoices);
});

it('can render the create page', function (): void {
    Livewire::test(CreateInvoice::class)
        ->assertOk();
});

it('can create an invoice', function (): void {
    $employee = Employee::factory()
        ->recycle($this->user->team)
        ->create();

    $invoiceNumber = 'INV-TEST-'.fake()->unique()->numerify('######');

    Livewire::test(CreateInvoice::class)
        ->fillForm([
            'invoice_number' => $invoiceNumber,
            'issued_by' => $employee->id,
            'invoice_date' => now()->format('Y-m-d'),
            'status' => InvoiceStatus::DRAFT->value,
            'currency' => 'HUF',
            'invoiceLines' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Invoice::class, [
        'invoice_number' => $invoiceNumber,
        'issued_by' => $employee->id,
        'status' => InvoiceStatus::DRAFT->value,
    ]);
});

it('validates required fields on create', function (): void {
    Livewire::test(CreateInvoice::class)
        ->fillForm([
            'invoice_number' => null,
            'issued_by' => null,
            'invoice_date' => null,
            'currency' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'invoice_number' => 'required',
            'issued_by' => 'required',
            'invoice_date' => 'required',
            'currency' => 'required',
        ]);
});

it('can render the edit page', function (): void {
    $invoice = Invoice::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditInvoice::class, ['record' => $invoice->getRouteKey()])
        ->assertOk();
});

it('can update an invoice', function (): void {
    $invoice = Invoice::factory()
        ->recycle($this->user->team)
        ->create();

    $newInvoiceNumber = 'INV-UPDATED-'.fake()->unique()->numerify('######');

    Livewire::test(EditInvoice::class, ['record' => $invoice->getRouteKey()])
        ->fillForm([
            'invoice_number' => $newInvoiceNumber,
            'status' => InvoiceStatus::ISSUED->value,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Invoice::class, [
        'id' => $invoice->id,
        'invoice_number' => $newInvoiceNumber,
        'status' => InvoiceStatus::ISSUED->value,
    ]);
});

it('can render the view page', function (): void {
    $invoice = Invoice::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(ViewInvoice::class, ['record' => $invoice->getRouteKey()])
        ->assertOk();
});

it('can delete an invoice', function (): void {
    $invoice = Invoice::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditInvoice::class, ['record' => $invoice->getRouteKey()])
        ->callAction('delete');

    $this->assertSoftDeleted('invoices', [
        'id' => $invoice->id,
    ]);
});
