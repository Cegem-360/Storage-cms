<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\CnCode;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryCnCodes implements Tool
{
    public function description(): string
    {
        return 'Query CN codes (Kombinált Nómenklatúra kódok). Can search by code or description. Global reference data, not team-specific.';
    }

    public function handle(Request $request): string
    {
        $query = CnCode::query();

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $cnCodes = $query->limit(30)->get();

        if ($cnCodes->isEmpty()) {
            return 'Nem található KN kód a megadott szűrőkkel.';
        }

        $result = "KN kódok ({$cnCodes->count()} tétel):\n\n";

        foreach ($cnCodes as $cnCode) {
            $unit = $cnCode->supplementary_unit ?? '-';
            $result .= "- {$cnCode->code} | "
                ."Leírás: {$cnCode->description} | "
                ."Kiegészítő mértékegység: {$unit}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by CN code or description'),
        ];
    }
}
