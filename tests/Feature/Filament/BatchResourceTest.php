<?php

declare(strict_types=1);

use App\Enums\QualityStatus;
use App\Filament\Resources\Batches\Pages\CreateBatch;
use App\Filament\Resources\Batches\Pages\EditBatch;
use App\Filament\Resources\Batches\Pages\ListBatches;
use App\Models\Batch;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Batch Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListBatches::class)
            ->assertOk();
    });

    it('can list batches', function (): void {
        $batches = Batch::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListBatches::class)
            ->assertCanSeeTableRecords($batches);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateBatch::class)
            ->assertOk();
    });

    it('can create a batch', function (): void {
        $product = Product::factory()->recycle($this->user->team)->create();
        $supplier = Supplier::factory()->recycle($this->user->team)->create();

        Livewire::test(CreateBatch::class)
            ->fillForm([
                'batch_number' => 'BATCH-TEST-001',
                'product_id' => $product->id,
                'supplier_id' => $supplier->id,
                'quantity' => 100,
                'quality_status' => QualityStatus::PENDING_CHECK->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('batches', [
            'batch_number' => 'BATCH-TEST-001',
            'product_id' => $product->id,
            'supplier_id' => $supplier->id,
            'quantity' => 100,
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can render the edit page', function (): void {
        $batch = Batch::factory()->recycle($this->user->team)->create();

        Livewire::test(EditBatch::class, ['record' => $batch->getRouteKey()])
            ->assertOk();
    });

    it('can edit a batch', function (): void {
        $batch = Batch::factory()->recycle($this->user->team)->create();

        Livewire::test(EditBatch::class, ['record' => $batch->getRouteKey()])
            ->fillForm([
                'quantity' => 999,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($batch->fresh()->quantity)->toBe(999);
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateBatch::class)
            ->fillForm([
                'batch_number' => null,
                'product_id' => null,
                'supplier_id' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'batch_number' => 'required',
                'product_id' => 'required',
                'supplier_id' => 'required',
            ]);
    });

    it('validates unique batch_number within team on create', function (): void {
        $product = Product::factory()->recycle($this->user->team)->create();
        $supplier = Supplier::factory()->recycle($this->user->team)->create();

        Batch::factory()->recycle($this->user->team)->create([
            'batch_number' => 'DUPLICATE-BATCH',
        ]);

        Livewire::test(CreateBatch::class)
            ->fillForm([
                'batch_number' => 'DUPLICATE-BATCH',
                'product_id' => $product->id,
                'supplier_id' => $supplier->id,
                'quantity' => 10,
                'quality_status' => QualityStatus::PENDING_CHECK->value,
            ])
            ->call('create')
            ->assertHasFormErrors(['batch_number']);
    });

    it('allows same batch_number in different teams', function (): void {
        $otherUser = User::factory()->create();
        Batch::factory()->recycle($otherUser->team)->create([
            'batch_number' => 'SHARED-BATCH',
        ]);

        $product = Product::factory()->recycle($this->user->team)->create();
        $supplier = Supplier::factory()->recycle($this->user->team)->create();

        Livewire::test(CreateBatch::class)
            ->fillForm([
                'batch_number' => 'SHARED-BATCH',
                'product_id' => $product->id,
                'supplier_id' => $supplier->id,
                'quantity' => 10,
                'quality_status' => QualityStatus::PENDING_CHECK->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
    });

    it('validates unique batch_number allows the same record on edit', function (): void {
        $batch = Batch::factory()->recycle($this->user->team)->create([
            'batch_number' => 'EXISTING-BATCH',
        ]);

        Livewire::test(EditBatch::class, ['record' => $batch->getRouteKey()])
            ->fillForm([
                'batch_number' => 'EXISTING-BATCH',
                'quantity' => 50,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($batch->fresh()->quantity)->toBe(50);
    });
});
