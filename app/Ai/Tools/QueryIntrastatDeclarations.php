<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\IntrastatDeclaration;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryIntrastatDeclarations implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query Intrastat declarations (Intrastat nyilatkozatok). Can filter by direction, status, or period. Shows declaration details including totals.';
    }

    public function handle(Request $request): string
    {
        $query = IntrastatDeclaration::query()
            ->where('intrastat_declarations.team_id', $this->team->id)
            ->withCount('intrastatLines');

        if ($request['direction'] ?? null) {
            $query->where('direction', $request['direction']);
        }

        if ($request['status'] ?? null) {
            $query->where('status', $request['status']);
        }

        if ($request['year'] ?? null) {
            $query->where('reference_year', $request['year']);
        }

        if ($request['month'] ?? null) {
            $query->where('reference_month', $request['month']);
        }

        $declarations = $query->latest('declaration_date')->limit(30)->get();

        if ($declarations->isEmpty()) {
            return 'Nem található Intrastat nyilatkozat a megadott szűrőkkel.';
        }

        $result = "Intrastat nyilatkozatok ({$declarations->count()} tétel):\n\n";

        foreach ($declarations as $declaration) {
            $invoiceValue = number_format((float) $declaration->total_invoice_value, 0, ',', ' ');
            $statisticalValue = number_format((float) $declaration->total_statistical_value, 0, ',', ' ');
            $result .= "- #{$declaration->declaration_number} | "
                ."Irány: {$declaration->direction?->value} | "
                ."Időszak: {$declaration->reference_year}/{$declaration->reference_month} | "
                ."Dátum: {$declaration->declaration_date?->format('Y-m-d')} | "
                ."Számlaérték: {$invoiceValue} Ft | "
                ."Statisztikai érték: {$statisticalValue} Ft | "
                ."Nettó tömeg: {$declaration->total_net_mass} kg | "
                ."Tételek: {$declaration->intrastat_lines_count} | "
                ."Státusz: {$declaration->status?->value}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'direction' => $schema->string()->description('Filter by direction (dispatch, arrival)'),
            'status' => $schema->string()->description('Filter by status (draft, submitted, accepted, rejected)'),
            'year' => $schema->integer()->description('Filter by reference year (e.g. 2026)'),
            'month' => $schema->integer()->description('Filter by reference month (1-12)'),
        ];
    }
}
