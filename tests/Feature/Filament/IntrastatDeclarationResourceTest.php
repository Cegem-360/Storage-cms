<?php

declare(strict_types=1);

use App\Enums\IntrastatDirection;
use App\Enums\IntrastatStatus;
use App\Filament\Resources\IntrastatDeclarations\Pages\CreateIntrastatDeclaration;
use App\Filament\Resources\IntrastatDeclarations\Pages\EditIntrastatDeclaration;
use App\Filament\Resources\IntrastatDeclarations\Pages\ListIntrastatDeclarations;
use App\Models\IntrastatDeclaration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('IntrastatDeclaration Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListIntrastatDeclarations::class)
            ->assertOk();
    });

    it('can list intrastat declarations', function (): void {
        $declarations = IntrastatDeclaration::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListIntrastatDeclarations::class)
            ->assertCanSeeTableRecords($declarations);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateIntrastatDeclaration::class)
            ->assertOk();
    });

    it('can create an intrastat declaration', function (): void {
        Livewire::test(CreateIntrastatDeclaration::class)
            ->fillForm([
                'declaration_number' => 'INTRA-202602-ARR-001',
                'direction' => IntrastatDirection::ARRIVAL->value,
                'status' => IntrastatStatus::DRAFT->value,
                'reference_year' => 2026,
                'reference_month' => 2,
                'declaration_date' => '2026-02-16',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('intrastat_declarations', [
            'declaration_number' => 'INTRA-202602-ARR-001',
            'direction' => IntrastatDirection::ARRIVAL->value,
            'status' => IntrastatStatus::DRAFT->value,
            'reference_year' => 2026,
            'reference_month' => 2,
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can render the edit page', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditIntrastatDeclaration::class, ['record' => $declaration->getRouteKey()])
            ->assertOk();
    });

    it('can edit an intrastat declaration', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditIntrastatDeclaration::class, ['record' => $declaration->getRouteKey()])
            ->fillForm([
                'notes' => 'Updated notes for testing',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($declaration->fresh()->notes)->toBe('Updated notes for testing');
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateIntrastatDeclaration::class)
            ->fillForm([
                'declaration_number' => null,
                'direction' => null,
                'reference_year' => null,
                'reference_month' => null,
                'declaration_date' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'declaration_number' => 'required',
                'direction' => 'required',
                'reference_year' => 'required',
                'reference_month' => 'required',
                'declaration_date' => 'required',
            ]);
    });

    it('validates unique declaration_number within team on create', function (): void {
        IntrastatDeclaration::factory()
            ->recycle($this->user->team)
            ->create([
                'declaration_number' => 'DUPLICATE-DECL',
            ]);

        Livewire::test(CreateIntrastatDeclaration::class)
            ->fillForm([
                'declaration_number' => 'DUPLICATE-DECL',
                'direction' => IntrastatDirection::ARRIVAL->value,
                'status' => IntrastatStatus::DRAFT->value,
                'reference_year' => 2026,
                'reference_month' => 2,
                'declaration_date' => '2026-02-16',
            ])
            ->call('create')
            ->assertHasFormErrors(['declaration_number']);
    });

    it('allows same declaration_number in different teams', function (): void {
        $otherUser = User::factory()->create();
        IntrastatDeclaration::factory()
            ->recycle($otherUser->team)
            ->create([
                'declaration_number' => 'SHARED-DECL',
            ]);

        Livewire::test(CreateIntrastatDeclaration::class)
            ->fillForm([
                'declaration_number' => 'SHARED-DECL',
                'direction' => IntrastatDirection::DISPATCH->value,
                'status' => IntrastatStatus::DRAFT->value,
                'reference_year' => 2026,
                'reference_month' => 2,
                'declaration_date' => '2026-02-16',
            ])
            ->call('create')
            ->assertHasNoFormErrors();
    });

    it('validates unique declaration_number allows the same record on edit', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->recycle($this->user->team)
            ->create([
                'declaration_number' => 'EXISTING-DECL',
            ]);

        Livewire::test(EditIntrastatDeclaration::class, ['record' => $declaration->getRouteKey()])
            ->fillForm([
                'declaration_number' => 'EXISTING-DECL',
                'notes' => 'Some update',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($declaration->fresh()->notes)->toBe('Some update');
    });

    it('can delete an intrastat declaration', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditIntrastatDeclaration::class, ['record' => $declaration->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($declaration);
    });
});
