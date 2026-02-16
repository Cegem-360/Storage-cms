<?php

declare(strict_types=1);

use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Suppliers\Pages\EditSupplier;
use App\Filament\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\Suppliers\Pages\ViewSupplier;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Supplier Resource', function (): void {
    it('can list suppliers', function (): void {
        $suppliers = Supplier::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListSuppliers::class)
            ->assertCanSeeTableRecords($suppliers);
    });

    it('can create a supplier', function (): void {
        $newSupplier = Supplier::factory()
            ->recycle($this->user->team)
            ->make();

        Livewire::test(CreateSupplier::class)
            ->fillForm([
                'code' => $newSupplier->code,
                'company_name' => $newSupplier->company_name,
                'trade_name' => $newSupplier->trade_name,
                'contact_person' => $newSupplier->contact_person,
                'email' => $newSupplier->email,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('suppliers', [
            'code' => $newSupplier->code,
            'company_name' => $newSupplier->company_name,
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can edit a supplier', function (): void {
        $supplier = Supplier::factory()
            ->recycle($this->user->team)
            ->create(['phone' => null]);

        $updatedName = 'Updated Supplier Co.';

        Livewire::test(EditSupplier::class, ['record' => $supplier->getRouteKey()])
            ->fillForm([
                'company_name' => $updatedName,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($supplier->fresh()->company_name)->toBe($updatedName);
    });

    it('can view a supplier', function (): void {
        $supplier = Supplier::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ViewSupplier::class, ['record' => $supplier->getRouteKey()])
            ->assertOk();
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateSupplier::class)
            ->fillForm([
                'code' => null,
                'company_name' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'code' => 'required',
                'company_name' => 'required',
            ]);
    });

    it('can delete a supplier', function (): void {
        $supplier = Supplier::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditSupplier::class, ['record' => $supplier->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('suppliers', [
            'id' => $supplier->id,
        ]);
    });
});
