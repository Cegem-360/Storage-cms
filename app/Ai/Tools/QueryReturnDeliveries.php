<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\ReturnDelivery;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryReturnDeliveries implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query return deliveries (visszáruk). Can search by return number. Filter by status or type. Shows return details including reason and amounts.';
    }

    public function handle(Request $request): string
    {
        $query = ReturnDelivery::query()
            ->where('return_deliveries.team_id', $this->team->id)
            ->with([
                'order:id,order_number,customer_id,supplier_id',
                'order.customer:id,company_name',
                'order.supplier:id,company_name',
                'warehouse:id,name',
            ])
            ->withCount('returnDeliveryLines');

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('return_number', 'like', "%{$search}%");
            });
        }

        if ($request['status'] ?? null) {
            $query->where('status', $request['status']);
        }

        if ($request['type'] ?? null) {
            $query->where('type', $request['type']);
        }

        $returns = $query->latest('return_date')->limit(30)->get();

        if ($returns->isEmpty()) {
            return 'Nem található visszáru a megadott szűrőkkel.';
        }

        $result = "Visszáruk ({$returns->count()} tétel):\n\n";

        foreach ($returns as $return) {
            $partner = $return->order?->customer?->company_name
                ?? $return->order?->supplier?->company_name
                ?? '-';
            $result .= "- #{$return->return_number} | "
                ."Típus: {$return->type?->value} | "
                ."Partner: {$partner} | "
                ."Raktár: {$return->warehouse?->name} | "
                ."Dátum: {$return->return_date?->format('Y-m-d')} | "
                ."Ok: {$return->reason?->value} | "
                .'Összeg: '.number_format((float) $return->total_amount, 0, ',', ' ').' Ft | '
                ."Tételek: {$return->return_delivery_lines_count} | "
                ."Státusz: {$return->status?->value}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by return number'),
            'status' => $schema->string()->description('Filter by status (pending, processed, approved, rejected, restocked)'),
            'type' => $schema->string()->description('Filter by type (customer_return, supplier_return)'),
        ];
    }
}
