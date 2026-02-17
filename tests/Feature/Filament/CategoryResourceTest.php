<?php

declare(strict_types=1);

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Pages\ViewCategory;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Category Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListCategories::class)
            ->assertOk();
    });

    it('can list categories', function (): void {
        $categories = Category::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListCategories::class)
            ->assertCanSeeTableRecords($categories);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateCategory::class)
            ->assertOk();
    });

    it('can create a category', function (): void {
        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Electronics',
                'code' => 'ELEC01',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics',
            'code' => 'ELEC01',
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can create a category with a parent', function (): void {
        $parent = Category::factory()->recycle($this->user->team)->create();

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Subcategory',
                'code' => 'SUB001',
                'parent_id' => $parent->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', [
            'name' => 'Subcategory',
            'code' => 'SUB001',
            'parent_id' => $parent->id,
        ]);
    });

    it('can render the edit page', function (): void {
        $category = Category::factory()->recycle($this->user->team)->create();

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->assertOk();
    });

    it('can edit a category', function (): void {
        $category = Category::factory()->recycle($this->user->team)->create();

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Category',
                'code' => 'UPD001',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($category->fresh())
            ->name->toBe('Updated Category')
            ->code->toBe('UPD001');
    });

    it('can render the view page', function (): void {
        $category = Category::factory()->recycle($this->user->team)->create();

        Livewire::test(ViewCategory::class, ['record' => $category->getRouteKey()])
            ->assertOk();
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => null,
                'code' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'code' => 'required',
            ]);
    });

    it('validates required fields on edit', function (): void {
        $category = Category::factory()->recycle($this->user->team)->create();

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->fillForm([
                'name' => null,
                'code' => null,
            ])
            ->call('save')
            ->assertHasFormErrors([
                'name' => 'required',
                'code' => 'required',
            ]);
    });

    it('can delete a category', function (): void {
        $category = Category::factory()->recycle($this->user->team)->create();

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
        ]);
    });
});
