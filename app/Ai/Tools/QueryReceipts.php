<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Receipt;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryReceipts implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query receipts (bevételezések). Can search by receipt number or order number. Filter by status. Shows receipt details including supplier and total amount.';
    }

    public function handle(Request $request): string
    {
        $query = Receipt::query()
            ->where('receipts.team_id', $this->team->id)
            ->with([
                'order:id,order_number,supplier_id',
                'order.supplier:id,company_name',
                'warehouse:id,name',
                'receivedBy:id,name',
            ])
            ->withCount('receiptLines');

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('receipt_number', 'like', "%{$search}%")
                    ->orWhereHas('order', fn ($oq) => $oq->where('order_number', 'like', "%{$search}%"));
            });
        }

        if ($request['status'] ?? null) {
            $query->where('status', $request['status']);
        }

        $receipts = $query->latest('receipt_date')->limit(30)->get();

        if ($receipts->isEmpty()) {
            return 'Nem található bevételezés a megadott szűrőkkel.';
        }

        $result = "Bevételezések ({$receipts->count()} tétel):\n\n";

        foreach ($receipts as $receipt) {
            $supplier = $receipt->order?->supplier?->company_name ?? '-';
            $result .= "- #{$receipt->receipt_number} | "
                ."Rendelés: {$receipt->order?->order_number} | "
                ."Beszállító: {$supplier} | "
                ."Raktár: {$receipt->warehouse?->name} | "
                ."Dátum: {$receipt->receipt_date?->format('Y-m-d')} | "
                .'Összeg: '.number_format((float) $receipt->total_amount, 0, ',', ' ').' Ft | '
                ."Tételek: {$receipt->receipt_lines_count} | "
                ."Státusz: {$receipt->status?->value}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by receipt number or order number'),
            'status' => $schema->string()->description('Filter by status (pending, confirmed, rejected)'),
        ];
    }
}
