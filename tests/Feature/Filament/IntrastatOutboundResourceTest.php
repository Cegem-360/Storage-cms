<?php

declare(strict_types=1);

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
});
