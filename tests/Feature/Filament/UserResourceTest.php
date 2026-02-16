<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('User Resource', function (): void {
    it('can list users', function (): void {
        $users = User::factory()
            ->count(3)
            ->create(['team_id' => $this->user->team_id]);

        Livewire::test(ListUsers::class)
            ->assertCanSeeTableRecords($users);
    });

    it('can create a user', function (): void {
        $newUser = User::factory()->make([
            'team_id' => $this->user->team_id,
        ]);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => $newUser->name,
                'email' => $newUser->email,
                'password' => 'SecurePass123!',
                'is_super_admin' => false,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'name' => $newUser->name,
            'email' => $newUser->email,
        ]);
    });

    it('can edit a user', function (): void {
        $targetUser = User::factory()->create([
            'team_id' => $this->user->team_id,
        ]);

        $updatedName = 'Updated User Name';

        Livewire::test(EditUser::class, ['record' => $targetUser->getRouteKey()])
            ->fillForm([
                'name' => $updatedName,
                'password' => 'NewPassword123!',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($targetUser->fresh()->name)->toBe($updatedName);
    });

    it('can view a user', function (): void {
        $targetUser = User::factory()->create([
            'team_id' => $this->user->team_id,
        ]);

        Livewire::test(ViewUser::class, ['record' => $targetUser->getRouteKey()])
            ->assertOk();
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => null,
                'email' => null,
                'password' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
    });

    it('validates email format', function (): void {
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Test User',
                'email' => 'not-a-valid-email',
                'password' => 'SecurePass123!',
                'is_super_admin' => false,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'email']);
    });

    it('can delete a user', function (): void {
        $targetUser = User::factory()->create([
            'team_id' => $this->user->team_id,
        ]);

        Livewire::test(EditUser::class, ['record' => $targetUser->getRouteKey()])
            ->callAction('delete');

        $this->assertDatabaseMissing('users', [
            'id' => $targetUser->id,
        ]);
    });
});
