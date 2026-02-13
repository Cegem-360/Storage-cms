<?php

declare(strict_types=1);

use App\Enums\CustomerType;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

describe('Customer Model', function (): void {
    it('can be created with valid attributes', function (): void {
        $customer = Customer::factory()->create([
            'customer_code' => 'CUST-12345',
            'name' => 'Acme Corporation',
            'email' => 'john.doe@example.com',
            'phone' => '555-1234',
            'type' => CustomerType::RETAIL,
        ]);

        expect($customer->customer_code)->toBe('CUST-12345')
            ->and($customer->name)->toBe('Acme Corporation')
            ->and($customer->email)->toBe('john.doe@example.com')
            ->and($customer->phone)->toBe('555-1234')
            ->and($customer->type)->toBe(CustomerType::RETAIL);
    });

    it('requires a unique email', function (): void {
        Customer::factory()->create(['email' => 'duplicate@example.com']);

        expect(fn () => Customer::factory()->create(['email' => 'duplicate@example.com']))
            ->toThrow(QueryException::class);
    });

    it('requires a unique customer code', function (): void {
        Customer::factory()->create(['customer_code' => 'CUST-12345']);

        expect(fn () => Customer::factory()->create(['customer_code' => 'CUST-12345']))
            ->toThrow(QueryException::class);
    });

    it('can be soft deleted', function (): void {
        $customer = Customer::factory()->create();

        $customer->delete();

        expect($customer->trashed())->toBeTrue()
            ->and(Customer::withTrashed()->find($customer->id))->not->toBeNull()
            ->and(Customer::query()->find($customer->id))->toBeNull();
    });

    it('can be restored after soft deletion', function (): void {
        $customer = Customer::factory()->create();
        $customer->delete();

        $customer->restore();

        expect($customer->trashed())->toBeFalse()
            ->and(Customer::query()->find($customer->id))->not->toBeNull();
    });

    it('can be force deleted', function (): void {
        $customer = Customer::factory()->create();
        $customerId = $customer->id;

        $customer->forceDelete();

        expect(Customer::withTrashed()->find($customerId))->toBeNull();
    });
});

describe('Customer Relationships', function (): void {
    it('has many orders', function (): void {
        $customer = Customer::factory()->create();

        expect($customer->orders())->toBeInstanceOf(HasMany::class);
    });

    it('can have multiple orders associated', function (): void {
        $customer = Customer::factory()->create();
        $orders = Order::factory()->count(3)->create([
            'customer_id' => $customer->id,
        ]);

        expect($customer->orders)->toHaveCount(3)
            ->and($customer->orders->pluck('id')->toArray())->toBe($orders->pluck('id')->toArray());
    });

    it('returns empty collection when customer has no orders', function (): void {
        $customer = Customer::factory()->create();

        expect($customer->orders)->toBeEmpty();
    });
});

describe('Customer Helper Methods', function (): void {
    it('can check credit limit', function (): void {
        $customer = Customer::factory()->create([
            'credit_limit' => 10000,
            'balance' => 5000,
        ]);

        expect($customer->checkCreditLimit(3000))->toBeTrue()
            ->and($customer->checkCreditLimit(6000))->toBeFalse();
    });

    it('can update balance', function (): void {
        $customer = Customer::factory()->create([
            'balance' => 1000,
        ]);

        $customer->updateBalance(500);

        expect($customer->fresh()->balance)->toBe('1500.00');
    });

    it('can decrease balance', function (): void {
        $customer = Customer::factory()->create([
            'balance' => 1000,
        ]);

        $customer->updateBalance(-200);

        expect($customer->fresh()->balance)->toBe('800.00');
    });
});

describe('Customer Queries', function (): void {
    it('can search by email', function (): void {
        $customer = Customer::factory()->create(['email' => 'search@example.com']);
        Customer::factory()->count(5)->create();

        $found = Customer::query()->where('email', 'search@example.com')->first();

        expect($found->id)->toBe($customer->id);
    });

    it('can search by customer code', function (): void {
        $customer = Customer::factory()->create(['customer_code' => 'CUST-99999']);
        Customer::factory()->count(5)->create();

        $found = Customer::query()->where('customer_code', 'CUST-99999')->first();

        expect($found->id)->toBe($customer->id);
    });

    it('can search by name', function (): void {
        $customer = Customer::factory()->create(['name' => 'XYZ Corporation']);
        Customer::factory()->count(5)->create();

        $found = Customer::query()->where('name', 'XYZ Corporation')->first();

        expect($found->id)->toBe($customer->id);
    });

    it('can filter by type', function (): void {
        Customer::factory()->count(3)->create(['type' => CustomerType::RETAIL]);
        Customer::factory()->count(2)->create(['type' => CustomerType::WHOLESALE]);

        $retailCustomers = Customer::query()->where('type', CustomerType::RETAIL)->get();

        expect($retailCustomers)->toHaveCount(3);
    });
});

describe('Customer Data Validation', function (): void {
    it('stores billing address as array', function (): void {
        $billingAddress = [
            'street' => '123 Main St',
            'city' => 'Springfield',
            'state' => 'IL',
            'postal_code' => '62701',
            'country' => 'USA',
        ];

        $customer = Customer::factory()->create([
            'billing_address' => $billingAddress,
        ]);

        expect($customer->fresh()->billing_address)->toBe($billingAddress);
    });

    it('stores shipping address as array', function (): void {
        $shippingAddress = [
            'street' => '456 Oak Ave',
            'city' => 'Chicago',
            'state' => 'IL',
            'postal_code' => '60601',
            'country' => 'USA',
        ];

        $customer = Customer::factory()->create([
            'shipping_address' => $shippingAddress,
        ]);

        expect($customer->fresh()->shipping_address)->toBe($shippingAddress);
    });

    it('stores credit limit as decimal', function (): void {
        $customer = Customer::factory()->create([
            'credit_limit' => 15000.50,
        ]);

        expect($customer->fresh()->credit_limit)->toBe('15000.50');
    });

    it('stores balance as decimal', function (): void {
        $customer = Customer::factory()->create([
            'balance' => 2500.75,
        ]);

        expect($customer->fresh()->balance)->toBe('2500.75');
    });
});

describe('Customer Factory', function (): void {
    it('creates customers with faker data', function (): void {
        $customer = Customer::factory()->create();

        expect($customer->customer_code)->not->toBeNull()
            ->and($customer->name)->not->toBeNull()
            ->and($customer->email)->not->toBeNull()
            ->and($customer->phone)->not->toBeNull()
            ->and($customer->billing_address)->not->toBeNull()
            ->and($customer->shipping_address)->not->toBeNull()
            ->and($customer->credit_limit)->not->toBeNull()
            ->and($customer->balance)->not->toBeNull()
            ->and($customer->type)->not->toBeNull();
    });

    it('creates multiple customers with unique emails', function (): void {
        $customers = Customer::factory()->count(10)->create();

        $emails = $customers->pluck('email')->toArray();

        expect($emails)->toHaveCount(10)
            ->and(count($emails))->toBe(count(array_unique($emails)));
    });

    it('creates multiple customers with unique customer codes', function (): void {
        $customers = Customer::factory()->count(10)->create();

        $codes = $customers->pluck('customer_code')->toArray();

        expect($codes)->toHaveCount(10)
            ->and(count($codes))->toBe(count(array_unique($codes)));
    });
});

describe('Customer Timestamps', function (): void {
    it('sets created_at timestamp on creation', function (): void {
        $customer = Customer::factory()->create();

        expect($customer->created_at)->not->toBeNull()
            ->and($customer->created_at)->toBeInstanceOf(Carbon::class);
    });

    it('has updated_at timestamp', function (): void {
        $customer = Customer::factory()->create();

        expect($customer->updated_at)->not->toBeNull()
            ->and($customer->updated_at)->toBeInstanceOf(Carbon::class);
    });

    it('sets deleted_at timestamp on soft delete', function (): void {
        $customer = Customer::factory()->create();

        $customer->delete();

        expect($customer->deleted_at)->not->toBeNull()
            ->and($customer->deleted_at)->toBeInstanceOf(Carbon::class);
    });
});
