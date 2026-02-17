<?php

declare(strict_types=1);

use App\Filament\Resources\Teams\Pages\CreateTeam;
use App\Filament\Resources\Teams\Pages\EditTeam;
use App\Filament\Resources\Teams\Pages\ListTeams;
use App\Filament\Resources\Teams\TeamResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Pest\Plugins\Parallel\Handlers\Pest;

uses(RefreshDatabase::class);
uses(Pest::class)->in('MultiTenancy');

beforeEach(function (): void {
    $this->superAdmin = User::factory()->create(['is_super_admin' => true]);
    $this->actingAs($this->superAdmin);
});

describe('Team Filament Resource', function (): void {
    it('is accessible by super admin', function (): void {
        expect(TeamResource::canAccess())->toBeTrue();
    });

    it('is not accessible by regular user', function (): void {
        $user = User::factory()->create(['is_super_admin' => false]);
        $this->actingAs($user);

        expect(TeamResource::canAccess())->toBeFalse();
    });

    it('can list teams', function (): void {
        $teams = Team::factory()->count(3)->create();

        Livewire::test(ListTeams::class)
            ->assertCanSeeTableRecords($teams);
    });

    it('can create a team', function (): void {
        Livewire::test(CreateTeam::class)
            ->fillForm([
                'name' => 'Test Team',
                'slug' => 'test-team',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('teams', [
            'name' => 'Test Team',
            'slug' => 'test-team',
            'is_active' => true,
        ]);
    });

    it('can edit a team', function (): void {
        $team = Team::factory()->create(['name' => 'Original Name']);

        Livewire::test(EditTeam::class, ['record' => $team->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($team->fresh()->name)->toBe('Updated Name');
    });

    it('validates unique slug', function (): void {
        Team::factory()->create(['slug' => 'taken-slug']);

        Livewire::test(CreateTeam::class)
            ->fillForm([
                'name' => 'Another Team',
                'slug' => 'taken-slug',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasFormErrors(['slug' => 'unique']);
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateTeam::class)
            ->fillForm([
                'name' => null,
                'slug' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'slug' => 'required',
            ]);
    });

    it('validates required fields on edit', function (): void {
        $team = Team::factory()->create();

        Livewire::test(EditTeam::class, ['record' => $team->getRouteKey()])
            ->fillForm([
                'name' => null,
                'slug' => null,
            ])
            ->call('save')
            ->assertHasFormErrors([
                'name' => 'required',
                'slug' => 'required',
            ]);
    });

    it('can delete a team', function (): void {
        $team = Team::factory()->create();

        Livewire::test(EditTeam::class, ['record' => $team->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($team);
    });
});
