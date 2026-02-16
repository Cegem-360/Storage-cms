<?php

declare(strict_types=1);

use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Filament\Resources\Employees\Pages\ViewEmployee;
use App\Models\Employee;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Employee Resource', function (): void {
    it('can list employees', function (): void {
        $employees = Employee::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListEmployees::class)
            ->assertCanSeeTableRecords($employees);
    });

    it('can create an employee', function (): void {
        $warehouse = Warehouse::factory()
            ->recycle($this->user->team)
            ->create();

        $newEmployee = Employee::factory()
            ->recycle($this->user->team)
            ->make([
                'warehouse_id' => $warehouse->id,
                'user_id' => null,
            ]);

        Livewire::test(CreateEmployee::class)
            ->fillForm([
                'employee_code' => $newEmployee->employee_code,
                'first_name' => $newEmployee->first_name,
                'last_name' => $newEmployee->last_name,
                'position' => $newEmployee->position,
                'department' => $newEmployee->department,
                'warehouse_id' => $warehouse->id,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('employees', [
            'employee_code' => $newEmployee->employee_code,
            'first_name' => $newEmployee->first_name,
            'last_name' => $newEmployee->last_name,
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can edit an employee', function (): void {
        $employee = Employee::factory()
            ->recycle($this->user->team)
            ->create(['phone' => null]);

        $updatedFirstName = 'UpdatedFirstName';

        Livewire::test(EditEmployee::class, ['record' => $employee->getRouteKey()])
            ->fillForm([
                'first_name' => $updatedFirstName,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($employee->fresh()->first_name)->toBe($updatedFirstName);
    });

    it('can view an employee', function (): void {
        $employee = Employee::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ViewEmployee::class, ['record' => $employee->getRouteKey()])
            ->assertOk();
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateEmployee::class)
            ->fillForm([
                'employee_code' => null,
                'first_name' => null,
                'last_name' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'employee_code' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
            ]);
    });

    it('can delete an employee', function (): void {
        $employee = Employee::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditEmployee::class, ['record' => $employee->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('employees', [
            'id' => $employee->id,
        ]);
    });
});
