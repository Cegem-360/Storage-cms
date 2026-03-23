<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Batch;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryBatches implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query batches (sarzsok). Can search by batch number or product name. Shows product, quantity, expiry date, and quality status.';
    }

    public function handle(Request $request): string
    {
        $query = Batch::query()
            ->where('batches.team_id', $this->team->id)
            ->with(['product:id,name,sku', 'supplier:id,company_name']);

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('batch_number', 'like', "%{$search}%")
                    ->orWhereHas('product', fn ($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request['quality_status'] ?? null) {
            $query->where('quality_status', $request['quality_status']);
        }

        $batches = $query->latest('created_at')->limit(30)->get();

        if ($batches->isEmpty()) {
            return 'Nem található sarzs a megadott szűrőkkel.';
        }

        $result = "Sarzsok ({$batches->count()} tétel):\n\n";

        foreach ($batches as $batch) {
            $expiry = $batch->expiry_date?->format('Y-m-d') ?? 'N/A';
            $expired = $batch->isExpired() ? ' [LEJÁRT]' : '';
            $result .= "- #{$batch->batch_number} | "
                ."Termék: {$batch->product?->name} ({$batch->product?->sku}) | "
                ."Beszállító: {$batch->supplier?->company_name} | "
                ."Mennyiség: {$batch->quantity} | "
                ."Lejárat: {$expiry}{$expired} | "
                ."Minőség: {$batch->quality_status}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by batch number or product name'),
            'quality_status' => $schema->string()->description('Filter by quality status (approved, quarantine, rejected)'),
        ];
    }
}
