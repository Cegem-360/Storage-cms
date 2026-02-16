<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('API Tenant Isolation', function (): void {
    it('returns only the authenticated user team products via API', function (): void {
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();

        Product::factory()->count(3)->recycle($teamA)->create();
        Product::factory()->count(2)->recycle($teamB)->create();

        $userA = User::factory()->recycle($teamA)->create();
        Sanctum::actingAs($userA);

        $response = $this->getJson('/api/v1/products');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    });

    it('prevents accessing another team product by id', function (): void {
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();

        $productB = Product::factory()->recycle($teamB)->create();

        $userA = User::factory()->recycle($teamA)->create();
        Sanctum::actingAs($userA);

        $response = $this->getJson("/api/v1/products/{$productB->id}");

        $response->assertNotFound();
    });

    it('allows super admin to access any team product', function (): void {
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();

        Product::factory()->count(3)->recycle($teamA)->create();
        Product::factory()->count(2)->recycle($teamB)->create();

        $superAdmin = User::factory()->recycle($teamA)->create(['is_super_admin' => true]);
        Sanctum::actingAs($superAdmin);

        $response = $this->getJson('/api/v1/products');

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    });

    it('rejects user without team assignment', function (): void {
        $user = User::factory()->create(['team_id' => null]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/products');

        $response->assertForbidden();
    });

    it('rejects user with inactive team', function (): void {
        $team = Team::factory()->create(['is_active' => false]);
        $user = User::factory()->recycle($team)->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/products');

        $response->assertForbidden();
    });
});

describe('API Team Endpoint', function (): void {
    it('allows super admin to list teams', function (): void {
        Team::factory()->count(3)->create();
        $superAdmin = User::factory()->create(['is_super_admin' => true]);
        Sanctum::actingAs($superAdmin);

        $response = $this->getJson('/api/v1/teams');

        $response->assertOk()
            ->assertJsonCount(4, 'data'); // 3 + super admin's team
    });

    it('denies non-super-admin access to teams', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/teams');

        $response->assertForbidden();
    });

    it('allows super admin to create a team', function (): void {
        $superAdmin = User::factory()->create(['is_super_admin' => true]);
        Sanctum::actingAs($superAdmin);

        $response = $this->postJson('/api/v1/teams', [
            'name' => 'New Team',
            'slug' => 'new-team',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'New Team')
            ->assertJsonPath('data.slug', 'new-team');
    });
});
