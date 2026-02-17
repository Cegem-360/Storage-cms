<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Supplier;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QuerySuppliers implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query suppliers. Can search by name, filter by active status or country.';
    }

    public function handle(Request $request): string
    {
        $query = Supplier::query()
            ->where('suppliers.team_id', $this->team->id)
            ->withCount('orders');

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if (($request['active_only'] ?? null) !== null) {
            $query->where('is_active', (bool) $request['active_only']);
        }

        if ($request['country'] ?? null) {
            $query->where('country_code', $request['country']);
        }

        $suppliers = $query->limit(30)->get();

        if ($suppliers->isEmpty()) {
            return 'Nem található beszállító a megadott szűrőkkel.';
        }

        $result = "Beszállítók ({$suppliers->count()} tétel):\n\n";

        foreach ($suppliers as $supplier) {
            $status = $supplier->is_active ? 'Aktív' : 'Inaktív';
            $result .= "- {$supplier->company_name} ({$supplier->code}) | "
                ."Kapcsolattartó: {$supplier->contact_person} | "
                ."Email: {$supplier->email} | "
                ."Ország: {$supplier->country_code} | "
                ."Rendelések: {$supplier->orders_count} | "
                ."Értékelés: {$supplier->rating} | {$status}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by company name, code, or contact person'),
            'active_only' => $schema->boolean()->description('Only show active suppliers'),
            'country' => $schema->string()->description('Filter by country code (e.g., HU, DE, AT)'),
        ];
    }
}
