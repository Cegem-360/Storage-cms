<?php

declare(strict_types=1);

use App\Filament\Resources\IntrastatOutbounds\Pages\CreateIntrastatOutbound;
use App\Filament\Resources\IntrastatOutbounds\Pages\EditIntrastatOutbound;
use App\Filament\Resources\IntrastatOutbounds\Pages\ListIntrastatOutbounds;
use App\Models\IntrastatDeclaration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('IntrastatOutbound Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListIntrastatOutbounds::class)
            ->assertOk();
    });

    it('can list intrastat outbound declarations', function (): void {
        $declarations = IntrastatDeclaration::factory()
            ->count(3)
            ->dispatch()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListIntrastatOutbounds::class)
            ->assertCanSeeTableRecords($declarations);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateIntrastatOutbound::class)
            ->assertOk();
    });

    it('can render the edit page', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->dispatch()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditIntrastatOutbound::class, ['record' => $declaration->getRouteKey()])
            ->assertOk();
    });

    it('can edit an intrastat outbound', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->dispatch()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditIntrastatOutbound::class, ['record' => $declaration->getRouteKey()])
            ->call('save')
            ->assertHasNoFormErrors();
    });

    it('can delete an intrastat outbound', function (): void {
        $declaration = IntrastatDeclaration::factory()
            ->dispatch()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditIntrastatOutbound::class, ['record' => $declaration->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($declaration);
    });
});
