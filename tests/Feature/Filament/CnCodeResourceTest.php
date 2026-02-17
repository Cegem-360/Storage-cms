<?php

declare(strict_types=1);

use App\Filament\Resources\CnCodes\Pages\CreateCnCode;
use App\Filament\Resources\CnCodes\Pages\EditCnCode;
use App\Filament\Resources\CnCodes\Pages\ListCnCodes;
use App\Models\CnCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('CnCode Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListCnCodes::class)
            ->assertOk();
    });

    it('can list cn codes', function (): void {
        $cnCodes = CnCode::factory()
            ->count(3)
            ->create();

        Livewire::test(ListCnCodes::class)
            ->assertCanSeeTableRecords($cnCodes);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateCnCode::class)
            ->assertOk();
    });

    it('can create a cn code', function (): void {
        Livewire::test(CreateCnCode::class)
            ->fillForm([
                'code' => '12345678',
                'description' => 'Test CN Code Description',
                'supplementary_unit' => 'kg',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('cn_codes', [
            'code' => '12345678',
            'description' => 'Test CN Code Description',
            'supplementary_unit' => 'kg',
        ]);
    });

    it('can render the edit page', function (): void {
        $cnCode = CnCode::factory()->create();

        Livewire::test(EditCnCode::class, ['record' => $cnCode->getRouteKey()])
            ->assertOk();
    });

    it('can edit a cn code', function (): void {
        $cnCode = CnCode::factory()->create();

        Livewire::test(EditCnCode::class, ['record' => $cnCode->getRouteKey()])
            ->fillForm([
                'description' => 'Updated Description',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($cnCode->fresh()->description)->toBe('Updated Description');
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateCnCode::class)
            ->fillForm([
                'code' => null,
                'description' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'code' => 'required',
                'description' => 'required',
            ]);
    });

    it('validates unique code on create', function (): void {
        CnCode::factory()->create(['code' => '99887766']);

        Livewire::test(CreateCnCode::class)
            ->fillForm([
                'code' => '99887766',
                'description' => 'Another CN Code',
            ])
            ->call('create')
            ->assertHasFormErrors(['code' => 'unique']);
    });

    it('validates code must be exactly 8 characters', function (): void {
        Livewire::test(CreateCnCode::class)
            ->fillForm([
                'code' => '1234',
                'description' => 'Short code test',
            ])
            ->call('create')
            ->assertHasFormErrors(['code']);
    });

    it('validates unique code allows the same record on edit', function (): void {
        $cnCode = CnCode::factory()->create(['code' => '11223344']);

        Livewire::test(EditCnCode::class, ['record' => $cnCode->getRouteKey()])
            ->fillForm([
                'code' => '11223344',
                'description' => 'Updated description',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($cnCode->fresh()->description)->toBe('Updated description');
    });

    it('can delete a cn code', function (): void {
        $cnCode = CnCode::factory()->create();

        Livewire::test(EditCnCode::class, ['record' => $cnCode->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($cnCode);
    });
});
