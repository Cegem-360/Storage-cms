<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Employee;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryEmployees implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query employees (alkalmazottak). Can search by name, employee code, or department. Shows employee details.';
    }

    public function handle(Request $request): string
    {
        $query = Employee::query()
            ->where('employees.team_id', $this->team->id);

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request['is_active'] ?? null) {
            $query->where('is_active', $request['is_active'] === 'true');
        }

        $employees = $query->limit(30)->get();

        if ($employees->isEmpty()) {
            return 'Nem található alkalmazott a megadott szűrőkkel.';
        }

        $result = "Alkalmazottak ({$employees->count()} fő):\n\n";

        foreach ($employees as $employee) {
            $status = $employee->is_active ? 'Aktív' : 'Inaktív';
            $result .= "- {$employee->last_name} {$employee->first_name} (Kód: {$employee->employee_code}) | "
                ."Pozíció: {$employee->position} | "
                ."Osztály: {$employee->department} | "
                ."Email: {$employee->email} | "
                ."Belépés: {$employee->hire_date?->format('Y-m-d')} | "
                ."Státusz: {$status}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by name, employee code, or department'),
            'is_active' => $schema->string()->description('Filter by active status (true/false)'),
        ];
    }
}
