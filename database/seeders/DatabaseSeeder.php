<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Team;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $team = Team::factory()->create(['name' => 'Default Team', 'slug' => 'default']);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'team_id' => $team->id,
        ]);

        $products = Product::factory()->count(10)->recycle($team)->create();

        $manager = Employee::factory()->recycle($team)->create([
            'warehouse_id' => null,
        ]);
        $warehouses = Warehouse::factory()
            ->count(2)
            ->recycle($team)
            ->for($manager, 'manager')
            ->has(Employee::factory()->count(3)->recycle($team))
            ->create();
        $warehouses->each(fn (Warehouse $warehouse) => Stock::factory()
            ->count(10)
            ->recycle($team)
            ->for($warehouse, 'warehouse')
            ->state(new Sequence(
                fn (Sequence $sequence): array => [
                    'product_id' => $products->get($sequence->index)->id,
                ]
            ))->create());

        Customer::factory()->count(10)->recycle($team)->create();

        $this->call([
            IntraStatSeeder::class,
        ]);
    }
}
