<?php

declare(strict_types=1);

use App\Filament\Resources\IntrastatInbounds\Pages\ListIntrastatInbounds;
use App\Models\IntrastatDeclaration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
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
});
