<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Team;
use App\Models\Warehouse;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryWarehouses implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query warehouses. Can search by name or code. Shows stock summary, capacity, and active status.';
    }

    public function handle(Request $request): string
    {
        $query = Warehouse::query()
            ->where('warehouses.team_id', $this->team->id)
            ->with(['manager:id,name'])
            ->withCount('stocks')
            ->withSum('stocks', 'quantity');

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if (($request['is_active'] ?? null) !== null) {
            $query->where('is_active', (bool) $request['is_active']);
        }

        $warehouses = $query->limit(30)->get();

        if ($warehouses->isEmpty()) {
            return 'Nem található raktár a megadott szűrőkkel.';
        }

        $result = "Raktárak ({$warehouses->count()} tétel):\n\n";

        foreach ($warehouses as $warehouse) {
            $status = $warehouse->is_active ? 'Aktív' : 'Inaktív';
            $totalQuantity = (int) $warehouse->stocks_sum_quantity;
            $result .= "- {$warehouse->name} (Kód: {$warehouse->code}) | "
                ."Típus: {$warehouse->type?->value} | "
                ."Kapacitás: {$warehouse->capacity} | "
                ."Készlettételek: {$warehouse->stocks_count} | "
                ."Összmennyiség: {$totalQuantity} | "
                ."Vezető: {$warehouse->manager?->name} | {$status}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by warehouse name or code'),
            'is_active' => $schema->boolean()->description('Filter by active status'),
        ];
    }
}
