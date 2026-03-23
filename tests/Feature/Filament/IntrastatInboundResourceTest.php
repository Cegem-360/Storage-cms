<?php

declare(strict_types=1);

use App\Filament\Resources\IntrastatInbounds\Pages\CreateIntrastatInbound;
use App\Filament\Resources\IntrastatInbounds\Pages\EditIntrastatInbound;
use App\Filament\Resources\IntrastatInbounds\Pages\ListIntrastatInbounds;
use App\Models\IntrastatDeclaration;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Filament::setTenant($this->user->team);
    Filament::bootCurrentPanel();
});

describe('IntrastatInbound Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListIntrastatInbounds::class)
            ->assertOk();
    });

    it('can list intrastat inbound declarations', function (): void {
        $declarations = IntrastatDeclaration::factory()
            ->count(3)
            ->arrival()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListIntrastatInbounds::class)
            ->assertCanSeeTableRecords($declarations);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateIntrastatInbound::class)
            ->assertOk();
    });

    it('can render the edit page', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->arrival()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditIntrastatInbound::class, ['record' => $declaration->getRouteKey()])
            ->assertOk();
    });

    it('can edit an intrastat inbound', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->arrival()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditIntrastatInbound::class, ['record' => $declaration->getRouteKey()])
            ->call('save')
            ->assertHasNoFormErrors();
    });

    it('can delete an intrastat inbound', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->arrival()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditIntrastatInbound::class, ['record' => $declaration->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($declaration);
    });
});
