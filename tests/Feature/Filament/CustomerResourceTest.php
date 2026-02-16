<?php

declare(strict_types=1);

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Customer Resource', function (): void {
    it('can list customers', function (): void {
        $customers = Customer::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListCustomers::class)
            ->assertCanSeeTableRecords($customers);
    });

    it('can create a customer', function (): void {
        $newCustomer = Customer::factory()
            ->recycle($this->user->team)
            ->make();

        Livewire::test(CreateCustomer::class)
            ->fillForm([
                'customer_code' => $newCustomer->customer_code,
                'name' => $newCustomer->name,
                'email' => $newCustomer->email,
                'type' => $newCustomer->type,
                'credit_limit' => $newCustomer->credit_limit,
                'balance' => $newCustomer->balance,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('customers', [
            'customer_code' => $newCustomer->customer_code,
            'name' => $newCustomer->name,
            'email' => $newCustomer->email,
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can edit a customer', function (): void {
        $customer = Customer::factory()
            ->recycle($this->user->team)
            ->create(['phone' => null]);

        $updatedName = 'Updated Customer Name';

        Livewire::test(EditCustomer::class, ['record' => $customer->getRouteKey()])
            ->fillForm([
                'name' => $updatedName,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($customer->fresh()->name)->toBe($updatedName);
    });

    it('can view a customer', function (): void {
        $customer = Customer::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ViewCustomer::class, ['record' => $customer->getRouteKey()])
            ->assertOk();
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateCustomer::class)
            ->fillForm([
                'customer_code' => null,
                'name' => null,
                'email' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'customer_code' => 'required',
                'name' => 'required',
                'email' => 'required',
            ]);
    });

    it('validates email format', function (): void {
        Livewire::test(CreateCustomer::class)
            ->fillForm([
                'customer_code' => 'CUST-99999',
                'name' => 'Test Customer',
                'email' => 'not-a-valid-email',
                'credit_limit' => 0,
                'balance' => 0,
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'email']);
    });

    it('can delete a customer', function (): void {
        $customer = Customer::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditCustomer::class, ['record' => $customer->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('customers', [
            'id' => $customer->id,
        ]);
    });
});
