<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Inventory;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryInventories implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query inventories (leltárak). Can filter by status or type. Shows inventory number, date, conducted by, status, and variance summary.';
    }

    public function handle(Request $request): string
    {
        $query = Inventory::query()
            ->where('inventories.team_id', $this->team->id)
            ->with(['warehouse:id,name', 'conductedBy:id,name'])
            ->withCount('inventoryLines');

        if ($request['status'] ?? null) {
            $query->where('status', $request['status']);
        }

        if ($request['type'] ?? null) {
            $query->where('type', $request['type']);
        }

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('inventory_number', 'like', "%{$search}%");
            });
        }

        $inventories = $query->latest('inventory_date')->limit(30)->get();

        if ($inventories->isEmpty()) {
            return 'Nem található leltár a megadott szűrőkkel.';
        }

        $result = "Leltárak ({$inventories->count()} tétel):\n\n";

        foreach ($inventories as $inventory) {
            $result .= "- #{$inventory->inventory_number} | "
                ."Raktár: {$inventory->warehouse?->name} | "
                ."Dátum: {$inventory->inventory_date?->format('Y-m-d')} | "
                ."Leltárazó: {$inventory->conductedBy?->name} | "
                ."Típus: {$inventory->type?->value} | "
                ."Tételek: {$inventory->inventory_lines_count} | "
                ."Státusz: {$inventory->status?->value}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by inventory number'),
            'status' => $schema->string()->description('Filter by status (draft, in_progress, completed, approved)'),
            'type' => $schema->string()->description('Filter by type (full, partial, cycle)'),
        ];
    }
}
