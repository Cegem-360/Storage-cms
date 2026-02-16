<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

describe('Team Global Scope', function (): void {
    it('filters records by the authenticated user team', function (): void {
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();

        Product::factory()->count(3)->recycle($teamA)->create();
        Product::factory()->count(2)->recycle($teamB)->create();
        /** @var User $userA */
        $userA = User::factory()->recycle($teamA)->create();
        actingAs($userA);

        expect(Product::query()->count())->toBe(3);
    });

    it('allows super admin to see all records', function (): void {
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();

        Product::factory()->count(3)->recycle($teamA)->create();
        Product::factory()->count(2)->recycle($teamB)->create();
        /** @var User $superAdmin */
        $superAdmin = User::factory()->recycle($teamA)->create(['is_super_admin' => true]);
        actingAs($superAdmin);

        expect(Product::query()->count())->toBe(5);
    });

    it('auto-sets team_id on model creation for authenticated user', function (): void {

        $team = Team::factory()->create();
        /** @var User $user */
        $user = User::factory()->recycle($team)->create();
        actingAs($user);

        $warehouse = Warehouse::factory()->create(['team_id' => null]);

        expect($warehouse->fresh()->team_id)->toBe($team->id);
    });

    it('does not override explicit team_id on creation', function (): void {
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();
        /** @var User $user */
        $user = User::factory()->recycle($teamA)->create(['is_super_admin' => true]);
        actingAs($user);

        $warehouse = Warehouse::factory()->create(['team_id' => $teamB->id]);

        expect($warehouse->fresh()->team_id)->toBe($teamB->id);
    });

    it('scopes across multiple models consistently', function (): void {
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();

        Product::factory()->count(2)->recycle($teamA)->create();
        Customer::factory()->count(3)->recycle($teamA)->create();
        Product::factory()->count(4)->recycle($teamB)->create();
        Customer::factory()->count(1)->recycle($teamB)->create();

        $userB = User::factory()->recycle($teamB)->create();
        /** @var User $userB */
        actingAs($userB);

        expect(Product::query()->count())->toBe(4)
            ->and(Customer::query()->count())->toBe(1);
    });

    it('returns no records when user has no matching team data', function (): void {
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();

        Product::factory()->count(5)->recycle($teamA)->create();

        $userB = User::factory()->recycle($teamB)->create();
        /** @var User $userB */
        actingAs($userB);

        expect(Product::query()->count())->toBe(0);
    });
});
